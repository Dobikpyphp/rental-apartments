<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Apartment; // Не забываем про use!

class ApartmentController extends Controller
{
    public function index(Request $request)
    {
        // dd($request->all());
        // 1. ВАЛИДАЦИЯ (Мягкая защита от некорректных данных в URL)
        // Мы используем nullable, чтобы пользователь мог не заполнять эти поля
        $request->validate([
            'price_to'  => 'nullable|numeric|min:0',        // Цена должна быть числом и не отрицательной
            'rooms'     => 'nullable|integer|min:1',        // Комнаты должны быть целым числом от 1
            'check_in'  => 'nullable|date',                  // Должно быть корректной датой
            'check_out' => 'nullable|date|after_or_equal:check_in', // Выезд не раньше заезда
            'wifi'      => 'nullable|boolean',              // Только true/false (или 1/0)
            'ac'        => 'nullable|boolean',              // Только true/false (или 1/0)
        ]);
        $apartments = Apartment::query()
            ->with('images') // <--- Жадная загрузка (Eager Loading)Что делает: Говорит Laravel: «Когда будешь доставать квартиры, сразу достань и все связанные с ними фотографии из таблицы images
            //Зачем: Чтобы в шаблоне Blade, когда ты пишешь $apt->images, Laravel не бегал в базу за каждой картинкой отдельно, а взял их из уже готового списка в памяти.
            ->where('active', true) //Что делает: Добавляет в SQL-запрос условие WHERE active = 1. Зачем: Чтобы на сайт не попали квартиры, которые владелец временно скрыл (выключил).
            // --- НОВАЯ ЛОГИКА: Фильтр по доступности дат ---
            //$request->filled('check_in') — возвращает true, только если поле есть в запросе И оно не пустое. Метод filled() умеет принимать не только одну строку, но и массив полей.
            ->when($request->filled(['check_in', 'check_out']), function ($query) use ($request) {
                $checkIn = $request->check_in;
                $checkOut = $request->check_out;

                // Мы ищем квартиры, у которых НЕТ бронирований, пересекающихся с этими датами
                //Метод whereDoesntHave() Если whereHas() говорит: «Найди мне записи, у которых ЕСТЬ связанная запись с такими-то условиями», то whereDoesntHave() делает ровно противоположное.
                //Простыми словами: Это фильтр «от обратного». Он говорит: «Дай мне все Квартиры, у которых НЕТ ни одного Бронирования, подходящего под эти условия».
                return $query->whereDoesntHave('bookings', function ($q) use ($checkIn, $checkOut) { //'bookings': Laravel смотрит в модель Apartment и находит там связь bookings(). Он понимает, что нужно идти в таблицу бронирований. function ($q) { ... }: Внутри этой функции ты описываешь «плохую» бронь. То есть ты описываешь те условия, при которых квартира считается занятой (пересечение дат).
                    $q->where(function ($subQuery) use ($checkIn, $checkOut) {
                        $subQuery->where('check_in', '<', $checkOut)
                            ->where('check_out', '>', $checkIn);
                    });
                    $q->whereNotIn('status', ['cancelled']); // Это говорит базе данных: «Меня не интересуют бронирования, у которых в колонке status написано 'cancelled'».
                });
            })
            // Фильтр по цене (до)
            ->when($request->price_to, function ($query, $price) { //Условие ($request->price_to): Laravel проверяет, пришло ли в запросе поле price_to. Если пользователь оставил поле цены пустым, то $request->price_to будет равно null (ложь).
                return $query->where('price_per_night', '<=', $price); //Действие (функция): Если цена введена (истина), Laravel выполняет функцию внутри. $query — это текущий объект запроса. $price — это то самое значение, которое ввел пользователь.
            })
            // Фильтр по комнатам
            ->when($request->rooms, function ($query, $rooms) {
                return $query->where('rooms', $rooms);
            })
            // Фильтр по удобствам (например, если передано wifi=1)
            ->when($request->wifi, function ($query) {
                return $query->where('has_wifi', true);
            })
            ->orderBy('created_at', 'desc') //Что делает: Сортирует результат. desc (Descending): По убыванию. То есть самые свежедобавленные квартиры (с самой поздней датой создания) будут первыми в списке.
            ->get(); //Метод ->get() — это команда «Огонь!». Только в этот момент Laravel отправляет итоговый, собранный SQL-запрос в базу данных MySQL и возвращает результат в виде коллекции объектов.

        return view('apartments.index', compact('apartments'));
    }


    public function show(int $id)
    {
        // 1. Загружаем квартиру вместе с её изображениями (Eager Loading)
        $apartment = Apartment::with('images')->findOrFail($id);
        // $apartment = Apartment::where('active', true)->orderBy('created_at', 'desc')->get();
        // Добавил фильтр 'active', чтобы вы могли скрывать квартиры из каталога

        // Получаем все бронирования этой квартиры
        $bookings = $apartment->bookings()->where('status', 'confirmed')->get();

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

//Итого: как это видит база данных (SQL)
// SELECT * FROM apartments 
// WHERE active = 1 
//   AND price_per_night <= 5000 
//   AND rooms = 2 
//   AND has_wifi = 1 
// ORDER BY created_at DESC;

// -- А затем отдельным запросом для жадной загрузки:
// SELECT * FROM images WHERE apartment_id IN (1, 5, 12...);
