<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Apartment;
use App\Jobs\SendTelegramNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        // 1. Валидация
        $validated = $request->validate([
            'apartment_id' => 'required|integer|exists:apartments,id', // exists проверяет, есть ли такая квартира в БД
            'check_in'    => 'required|date|after_or_equal:today', // Дата не раньше сегодняшней
            'check_out'   => 'required|date|after:check_in', // Дата выезда строго после заезда
        ]);


        // 2. Проверка авторизации (до транзакции, чтобы не блокировать зря)
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Пожалуйста, войдите в систему, чтобы забронировать квартиру.');
        }

        $user = Auth::user(); // Получаем объект пользователя

        try {
            // 3. Всё, что меняет данные, оборачиваем в транзакцию
            $booking = DB::transaction(
                function () use ($validated, $user) {
                    // 3.1. Блокируем строку квартиры. Теперь другие запросы будут ждать.
                    $apartment = Apartment::where('id', $validated['apartment_id'])
                        ->lockForUpdate()
                        ->firstOrFail();

                    $checkIn = $validated['check_in'];
                    $checkOut = $validated['check_out'];
                    // 3.2. Проверяем пересечение дат (теперь это безопасно)
                    $exists = Booking::where('apartment_id', $apartment->id)
                        ->where('status', '!=', 'cancelled')
                        ->where(function ($query) use ($checkIn, $checkOut) {
                            $query->where('check_in', '<', $checkOut)
                                ->where('check_out', '>', $checkIn);
                        })
                        ->exists();

                    if ($exists) {
                        // Бросаем исключение, чтобы транзакция откатилась
                        throw new \Exception('Квартира занята на выбранные даты!');
                    }

                    // 1. Проверяем, есть ли у этого пользователя профиль гостя
                    $guest = $user->guest; // Используем связь, которую создали в модели User

                    if (!$guest) {
                        $guest = Guest::create([
                            // Если $user->name пустой, запишем 'Гость'
                            'user_id'   => $user->id, // Привязываем гостя к юзеру!
                            'full_name' => $user->name ?? 'Гость без имени',
                            'email'     => $user->email ?? null,
                            'phone'     => null,
                            'rating'    => 5,
                        ]);
                    }

                    // 4. РАСЧЕТ СТОИМНОСТИ
                    // Считаем разницу в днях с помощью Carbon
                    $startDate = Carbon::parse($checkIn);
                    $endDate = Carbon::parse($checkOut);
                    $nights = $startDate->diffInDays($endDate);
                    $totalPrice = $nights * $apartment->price_per_night;

                    // Если мы дошли сюда — значит квартира свободна! 

                    // 3.5. Создаем бронирование
                    return Booking::create([
                        'apartment_id' => $apartment->id,
                        'user_id'      => $user->id,
                        'guest_id'     => $guest->id,
                        'check_in'     => $checkIn,
                        'check_out'    => $checkOut,
                        'total_price'  => $totalPrice,
                        'status'       => 'pending',
                    ]);
                }
            );

            // 4. Отправляем уведомление (вне транзакции, чтобы не блокировать)
            SendTelegramNotification::dispatch($booking->apartment, $booking);

            return redirect()->route('apartments.index')
                ->with('success', 'Заявка на бронирование отправлена!');
        } catch (\Exception $e) {
            // 5. Ловим исключение (включая наше "Квартира занята")
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function myBookings()
    {
        // Получаем бронирования только текущего авторизованного пользователя
        $bookings = Booking::with(['apartment'])
            ->where('user_id',  Auth::id())
            ->orderBy('check_in', 'desc')
            ->get();

        return view('apartments.my_bookings', compact('bookings'));
    }

    public function cancel(Booking $booking)
    {
        // Проверяем, что бронь принадлежит пользователю
        if ($booking->user_id !== Auth::id()) {
            return back()->with('error', 'Вы не можете отменить чужую бронь!');
        }

        // Разрешаем отмену, только если статус 'pending'
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Нельзя отменить уже подтвержденное бронирование.');
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Ваша заявка была отменена.');
    }
}
