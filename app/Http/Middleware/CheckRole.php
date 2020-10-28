<?php

namespace App\Http\Middleware;

//use App\Models\PasswordReset;

use Closure;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CheckRole
{

    public function handle($request, Closure $next, $role_slug)
    {

        if(Auth::user()->roles()->first()->slug != $role_slug){
            return redirect('/'.app()->getLocale().'/profile-author-information')->with('status', 'У вас недостаточно прав для доступа к выбранному разделу');
        }

        return $next($request);
    }
}
