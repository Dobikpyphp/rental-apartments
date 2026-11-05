<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // 1. Проверяем, авторизован ли пользователь вообще
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 2. Проверяем, входит ли роль пользователя в список разрешенных для этого маршрута
        // Например, если маршруту нужны ['admin', 'superadmin'], а у пользователя 'guest' - доступ закрыт
        if (!in_array($user->role, $roles)) {
            return redirect()->route('apartments.index')
                ->with('error', 'У вас недостаточно прав для доступа к этой странице.');
        }

        return $next($request);
    }
}
