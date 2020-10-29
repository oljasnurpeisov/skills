<?php

namespace App\Http\Middleware;

//use App\Models\PasswordReset;

use Closure;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AuthorActivate
{

    public function handle($request, Closure $next)
    {

        if(Auth::user()->is_activate == 0){
            return redirect('/'.app()->getLocale().'/')->with('status', 'У вас недостаточно прав для доступа к выбранному разделу');
        }

        return $next($request);
    }
}
