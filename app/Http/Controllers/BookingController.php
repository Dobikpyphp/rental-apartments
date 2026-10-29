<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // 2. Вытаскиваем данные из валидации в переменные (для удобства)
        $apartmentId = $validated['apartment_id'];
        $checkIn     = $validated['check_in'];
        $checkOut    = $validated['check_out'];

        // 3. ПРОВЕРКА ПЕРЕСЕЧЕНИЯ (тот самый алгоритм)
        $exists = Booking::where('apartment_id', $apartmentId) // Ищем брони этой квартиры
            ->where(function ($query) use ($checkIn, $checkOut) {
                // Сравнение: дата из базы < дата выезда пользователя И дата из базы > дата заезда пользователя
                $query->where('check_in', '<', $checkOut)
                    ->where('check_out', '>', $checkIn);
            })
            ->exists(); // Возвращает true, если нашли хотя бы одно пересечение

        // dd($sql);

        if ($exists) {
            // Если пересечение найдено, возвращаем назад с ошибкой
            return redirect()->back()
                ->withInput() // Чтобы пользователь не вводил всё заново
                ->with('error', 'Извините, квартира занята на выбранные даты!');
        }

        $user = Auth::user(); // Получаем объект пользователя

        // 1. Проверяем, есть ли у этого пользователя профиль гостя
        $guest = $user->guest; // Используем связь, которую создали в модели User

        if (!$guest) {
            $guest = Guest::create([
                // Если $user->name пустой, запишем 'Гость'
                'full_name' => $user->name ?? 'Гость без имени',
                'email'     => $user->email ?? null,
                'phone'     => null,
                'rating'    => 5,
            ]);
        }

        // Если мы дошли сюда — значит квартира свободна! 

        // 3. Теперь создаем бронирование, привязывая его к профилю ГОСТЯ
        Booking::create([
            'apartment_id' => $apartmentId,
            'user_id'      => $user->id,
            'guest_id'     => $guest->id, // ТЕПЕРЬ ПРИВЯЗАНО!
            'check_in'     => $checkIn,
            'check_out'    => $checkOut,
            'total_price'  => 0,
            'status'       => 'pending',
        ]);

        return redirect()->route('apartments.index')->with('success', 'Заявка на бронирование отправлена!');
    }
}
