<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, $type)
    {
        // Получаем текущего пользователя
        $user = Auth::user();

        // Проверяем, что пользователь авторизован и его тип соответствует требуемому
        if ($user && $user->userType && $user->userType->name === $type) {
            return $next($request);
        }

        // Если пользователь не авторизован или тип не совпадает, перенаправляем его
        return redirect('/');
    }
}
