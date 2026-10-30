<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Apartment;
use App\Models\Booking;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class IcalController extends Controller
{
    public function import(int $apartmentId)
    {
        $apartment = Apartment::findOrFail($apartmentId);
        $url = $apartment->ical_url;

        if (!$url) {
            return back()->with('error', 'Ссылка на iCal не указана для этой квартиры!');
        }

        try {
            // ПРОВЕРКА: Если ссылка ведет на наш локальный сервер
            if (str_contains($url, '127.0.0.1') || str_contains($url, 'localhost')) {
                // Вместо HTTP-запроса просто вызываем метод этого же контроллера
                // (Или создаем отдельный метод для получения текста файла)
                $content = $this->getFakeIcalContent();
            } else {
                // Для реальных внешних сайтов (Авито и т.д.) используем HTTP
                $response = Http::get($url);
                $content = $response->body();
            }

            // 2. Разрезаем файл на отдельные события (блоки BEGIN:VEVENT ... END:VEVENT)
            $events = explode('BEGIN:VEVENT', $content);
            // Удаляем первый пустой элемент (заголовок файла)
            array_shift($events);

            $importedCount = 0;

            foreach ($events as $event) {
                // Извлекаем UID, DTSTART и DTEND с помощью регулярных выражений
                // Это поиск строки, которая начинается с UID: и заканчивается переносом строки
                preg_match('/UID:(.*)\r\n/', $event, $uidMatch);
                preg_match('/DTSTART:(.*)\r\n/', $event, $startMatch);
                preg_match('/DTEND:(.*)\r\n/', $event, $endMatch);

                if (isset($uidMatch[1], $startMatch[1], $endMatch[1])) {
                    $uid = trim($uidMatch[1]);

                    // Превращаем формат 20231101T000000Z в 2023-11-01
                    $checkIn = Carbon::parse($startMatch[1])->format('Y-m-d');
                    $checkOut = Carbon::parse($endMatch[1])->format('Y-m-d');

                    // 3. updateOrCreate — магия Laravel! 
                    // Если бронь с таким UID уже есть -> обновляем её. Если нет -> создаем.
                    Booking::updateOrCreate(
                        ['uid' => $uid], // По чему ищем дубликат
                        [
                            'apartment_id' => $apartment->id,
                            'check_in'     => $checkIn,
                            'check_out'    => $checkOut,
                            'status'       => 'confirmed', // Внешние брони считаем подтвержденными
                            'total_price'  => 0, // Цену из чужих календарей обычно не передают
                            'user_id'      => null, // Это внешний гость
                        ]
                    );
                    $importedCount++;
                }
            }

            return redirect()->back()->with('success', "Синхронизация завершена! Обновлено бронирований: $importedCount");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ошибка при импорте: ' . $e->getMessage());
        }
    }

    // Добавляем вспомогательный метод в этот же контроллер
    private function getFakeIcalContent()
    {
        $content = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:fake-service\r\n";
        $content .= "BEGIN:VEVENT\r\nUID:booking-123@external.com\r\nDTSTART:20261110T000000Z\r\nDTEND:20261115T000000Z\r\nSUMMARY:Занято через Авито\r\nEND:VEVENT\r\n";
        $content .= "END:VCALENDAR";
        return $content;
    }
}
