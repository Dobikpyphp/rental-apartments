<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__)) //Создаёт приложение
    ->withRouting(
        web: __DIR__ . '/../routes/web.php', //Подключает routes/web.php
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) { //Регистрирует middleware
        // Алиасы для middleware
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // CSRF-исключения (если нужно)
        // $middleware->validateCsrfTokens(except: ['api/*']);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Обработка 403 — редирект в каталог с сообщением
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e, $request) {
            return redirect()->route('apartments.index')
                ->with('error', 'У вас недостаточно прав для доступа к этой странице.');
        });
    })->create();
