<?php

namespace App\Services;

use App\Models\Apartment;
use App\Models\Booking;
use App\Models\Guest;
use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\DB;

class RealtyCalendarService
{
    /**
     * Главный метод: обрабатывает входящий Webhook
     */
    public function handleWebhook(array $payload): void
    {
        // 1. Проверяем, что это бронирование
        if (!isset($payload['event']) || !str_starts_with($payload['event'], 'booking.')) {
            Log::info('RealtyCalendar webhook: not a booking event', ['payload' => $payload]);
            return;
        }

        $data = $payload['data'] ?? [];
        $externalId = $data['id'] ?? null;

        if (!$externalId) {
            Log::error('RealtyCalendar webhook: no booking ID', ['payload' => $payload]);
            return;
        }

        // 2. Находим квартиру по ID RealtyCalendar
        $apartment = Apartment::where('realty_calendar_id', $data['apartment_id'])->first();

        if (!$apartment) {
            Log::error('RealtyCalendar webhook: apartment not found', [
                'realty_calendar_id' => $data['apartment_id'] ?? null,
            ]);
            return;
        }

        // 3. Создаём или обновляем гостя
        $guest = $this->findOrCreateGuest($data);

        // 4. Создаём или обновляем бронь
        Booking::updateOrCreate(
            ['external_id' => $externalId],
            [
                'apartment_id' => $apartment->id,
                'guest_id'     => $guest->id,
                'user_id'      => null, // гость не с нашего сайта
                'check_in'     => $data['check_in'],
                'check_out'    => $data['check_out'],
                'total_price'  => $data['total_price'] ?? 0,
                'status'       => $this->mapStatus($data['status'] ?? 'pending'),
                'source'       => 'realty_calendar',
            ]
        );
    }

    private function findOrCreateGuest(array $data): Guest
    {
        /**
         * Находит или создаёт гостя
         */
        $phone = $data['guest_phone'] ?? null;
        $email = $data['guest_email'] ?? null;

        if ($phone) {
            return Guest::firstOrCreate(
                ['phone' => $phone],
                [
                    'full_name' => $data['guest_name'] ?? 'Гость из RealtyCalendar',
                    'email'     => $email,
                    'rating'    => 5,
                ]
            );
        }

        // Если нет телефона — создаём «анонимного» гостя
        return Guest::create([
            'full_name' => $data['guest_name'] ?? 'Гость из RealtyCalendar',
            'email'     => $email,
            'phone'     => null,
            'rating'    => 5,
        ]);
    }

    /**
     * Преобразует статус из RealtyCalendar в ваш статус
     */
    private function mapStatus(string $status): string
    {
        return match ($status) {
            'confirmed' => 'confirmed',
            'cancelled' => 'cancelled',
            default     => 'pending',
        };
    }
}
