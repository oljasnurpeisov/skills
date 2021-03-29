<?php

namespace App\Http\Middleware;

//use App\Models\PasswordReset;

use Closure;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

/**
 * --------------------------------------------------------------------------
 *  CheckPermission
 * --------------------------------------------------------------------------
 *
 *  Проверка прав доступа
 *
 */

class CheckPermission
{
    /**
     * Перехват входящего запроса.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param $permission
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        if (!Auth::user()) {
            return redirect('/'.app()->getLocale().'/admin/login')->with('status', 'Пожалуйста авторизуйтесь');
        }

//        if (Auth::user()->deleted) {
//            return redirect('/')->with('status', 'Вы заблокированы. Пожалуйста обратитесь к администратору');
//        }

        if (!Auth::user()->can($permission)) {
            return redirect('/')->with('status', 'У вас недостаточно прав для доступа к выбранному разделу');
        }

        return $next($request);
    }
}
