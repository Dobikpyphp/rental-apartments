<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Проверяем, передан ли в ссылке секретный ключ ?admin=true
        if ($request->query('admin') === 'true') {
            return $next($request); // Пропускаем дальше к контроллеру
        }

        // Если ключа нет, отправляем на главную страницу с ошибкой
        return redirect('/')->with('error', 'Доступ запрещен! Вы не администратор.');
    }
}
