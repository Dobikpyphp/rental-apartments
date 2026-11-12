<?php

// namespace App\Services;

// use App\Models\Booking;
// use YooKassa\Client;
// use Illuminate\Support\Str;

// class YooKassaPaymentService
// {
//     private Client $client;

//     public function __construct()
//     {
//         $this->client = new Client();
//         $this->client->setAuth(
//             config('services.yookassa.shop_id'),
//             config('services.yookassa.secret_key')
//         );
//     }

//     /**
//      * Создаёт платёж в YooKassa и возвращает URL для редиректа.
//      *
//      * @param Booking $booking
//      * @return string
//      * @throws \Exception
//      */
//     public function createPayment(Booking $booking): string
//     {
//         // Ключ идемпотентности — UUID v4 (надёжнее, чем uniqid)
//         $idempotenceKey = Str::uuid()->toString();

//         try {
//             $response = $this->client->createPayment(
//                 [
//                     'amount' => [
//                         'value' => number_format($booking->total_price, 2, '.', ''),
//                         'currency' => 'RUB',
//                     ],
//                     'confirmation' => [
//                         'type' => 'redirect',
//                         'return_url' => route('payment.success', $booking->id),
//                     ],
//                     'capture' => true,
//                     'description' => 'Бронирование #' . $booking->id . ' — ' . $booking->apartment->title,
//                     'metadata' => [
//                         'booking_id' => $booking->id,
//                     ],
//                 ],
//                 $idempotenceKey
//             );

//             // Сохраняем ID платежа в бронь (статус пока 'pending')
//             $booking->update(['payment_id' => $response->getId()]);

//             // Возвращаем URL для перенаправления на оплату
//             return $response->getConfirmation()->getConfirmationUrl();
//         } catch (\Exception $e) {
//             // Логируем ошибку и пробрасываем дальше
//             \Log::error('YooKassa createPayment error: ' . $e->getMessage());
//             throw new \Exception('Не удалось создать платёж. Попробуйте позже.');
//         }
//     }
// }


// Что здесь важно:

// number_format($booking->total_price, 2, '.', '') — сумма должна быть строкой с двумя знаками после точки (например, "1500.00"), как требует API ЮKassa .

// metadata — можно передать ID брони, чтобы потом в вебхуке знать, какую именно бронь обновлять.

// return_url — куда ЮKassa вернёт пользователя после оплаты. Это должен быть HTTPS-адрес на реальном сервере .
