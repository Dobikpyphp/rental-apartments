<?php

namespace App\Http\Controllers;

use App\Models\Booking;
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

        if ($exists) {
            // Если пересечение найдено, возвращаем назад с ошибкой
            return redirect()->back()
                ->withInput() // Чтобы пользователь не вводил всё заново
                ->with('error', 'Извините, квартира занята на выбранные даты!');
        }

        // 4. Если мы дошли сюда — значит квартира свободна! 
        // Теперь создаем бронирование
        Booking::create([
            'apartment_id' => $apartmentId,
            'user_id'      => Auth::id(), // ID текущего залогиненного пользователя
            'check_in'     => $checkIn,
            'check_out'    => $checkOut,
            'total_price'  => 0, // Пока оставим 0, расчет цены сделаем в следующем шаге
            'status'       => 'pending',
        ]);

        return redirect()->route('apartments.index')->with('success', 'Заявка на бронирование отправлена!');
    }
}
