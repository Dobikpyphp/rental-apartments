<?php

namespace App\Services;

use App\Models\Booking;

class PaymentService
{
    /**
     * Симуляция создания платежа.
     * В реальном коде здесь был бы запрос к YooKassa.
     *
     * @return string URL для редиректа (в симуляции — на страницу успеха)
     */
    public function createPayment(Booking $booking): string
    {
        // Генерируем фейковый ID платежа
        $fakePaymentId = 'fake_' . uniqid();

        // Сразу помечаем бронь как оплаченную (подтверждённую)
        $booking->update([
            'status' => 'confirmed',
            'payment_id' => $fakePaymentId,
        ]);

        // Возвращаем URL на страницу "успеха" (можно на my.bookings)
        return route('my.bookings');
    }
}
