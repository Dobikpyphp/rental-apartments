<?php

namespace App\Jobs;

use App\Models\Apartment;
use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
// Тут внимательно: InteractsWithQueue и SerializesModels живут в разных папках!
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

// ... другие импорты ...
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendTelegramNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Apartment $apartment;
    public Booking $booking;

    // Передаем данные в задачу при создании
    public function __construct(Apartment $apartment, Booking $booking)
    {
        $this->apartment = $apartment;
        $this->booking = $booking;
    }

    public function handle()
    {
        try {
            $token = env('TELEGRAM_BOT_TOKEN');
            $chatId = env('TELEGRAM_CHAT_ID');

            $message = "🚀 *Новая бронь!* \n\n";
            $message .= "🏠 *Квартира:* " . $this->apartment->title . "\n";
            $message .= "📅 *Даты:* " . $this->booking->check_in . " --> " . $this->booking->check_out . "\n";
            $message .= "💰 *Сумма:* " . $this->booking->total_price . " руб.\n";
            $message .= "👤 *Гость:* " . ($this->booking->user->name ?? 'Не зарегистрирован');
            // ПРОВЕРКА: Если мы на локальном сервере, просто пишем в лог
            if (app()->environment('local')) {
                Log::info("SIMULATION: Сообщение в Telegram отправлено! Текст: " . $message);
                return; // Останавливаем выполнение, чтобы не было Timeout
            }
            Http::timeout(5)->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown',
            ]);
        } catch (\Exception $e) {
            Log::error("Ошибка в очереди Telegram: " . $e->getMessage());
        }
    }
}
