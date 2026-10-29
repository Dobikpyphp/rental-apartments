<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use Carbon\Carbon;
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

    public function exportIcal(Apartment $apartment)
    {
        // 1. Начало файла iCal
        $ical = "BEGIN:VCALENDAR\r\n";
        $ical .= "VERSION:2.0\r\n";
        $ical .= "PRODID:my-rental-site\r\n";

        // 2. Получаем только подтвержденные бронирования
        $bookings = $apartment->bookings()->where('status', 'confirmed')->get();

        foreach ($bookings as $booking) {
            $ical .= "BEGIN:VEVENT\r\n";
            $ical .= "SUMMARY:Reserved\r\n";

            // Превращаем дату из базы (2023-11-01) в формат iCal (20231101T000000Z)
            $start = Carbon::parse($booking->check_in)->format('Ymd\T000000\Z');
            $end = Carbon::parse($booking->check_out)->format('Ymd\T000000\Z');

            $ical .= "DTSTART:" . $start . "\r\n";
            $ical .= "DTEND:" . $end . "\r\n";
            $ical .= "END:VEVENT\r\n";
        }

        $ical .= "END:VCALENDAR";

        // 3. Возвращаем ответ как файл
        return response($ical)
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="calendar.ics"');
    }
}

//  \r (Carriage Return / Возврат каретки): В старых печатных машинках это была команда вернуть головку с текстом в самое начало строки.
// \n (Line Feed / Перевод строки): Это команда сдвинуть бумагу на одну строку вниз.
