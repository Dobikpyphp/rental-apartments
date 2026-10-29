<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Apartment; // Не забываем про use!

class ApartmentController extends Controller
{
    public function index()
    {
        // 1. Получаем все квартиры из базы данных
        $apartments = Apartment::all();

        // 2. Возвращаем страницу 'apartments.index' и передаем туда данные
        return view('apartments.index', compact('apartments'));
    }

    public function show(int $id)
    {
        $apartment = Apartment::findOrFail($id);

        // Получаем все бронирования этой квартиры
        $bookings = $apartment->bookings()->get();

        // Превращаем бронирования в простой массив дат: [['start' => '2023-10-10', 'end' => '2023-10-15'], ...]
        $blockedDates = $bookings->map(function ($booking) {
            return [
                'start' => $booking->check_in,
                'end' => $booking->check_out,
            ];
        });

        return view('apartments.show', compact('apartment', 'blockedDates'));
    }
}
