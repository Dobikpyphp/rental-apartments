<?php

namespace App\Http\Controllers;

use App\Services\RealtyCalendarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RealtyCalendarWebhookController extends Controller
{
    public function handle(Request $request, RealtyCalendarService $service)
    {
        // 1. Логируем входящий запрос (для отладки)
        Log::info('RealtyCalendar webhook received', [
            'payload' => $request->all(),
        ]);

        // 2. Проверяем секретный токен (если RealtyCalendar его присылает)
        $secret = config('services.realty_calendar.webhook_secret');
        if ($secret && $request->header('X-Webhook-Secret') !== $secret) {
            Log::warning('RealtyCalendar webhook: invalid secret');
            return response('Forbidden', 403);
        }

        // 3. Обрабатываем
        try {
            $service->handleWebhook($request->all());
            return response('OK', 200);
        } catch (\Exception $e) {
            Log::error('RealtyCalendar webhook error: ' . $e->getMessage(), [
                'payload' => $request->all(),
            ]);
            return response('Error', 500);
        }
    }
}
