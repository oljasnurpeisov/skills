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
            return redirect('/'.app()->getLocale().'/profile-author-information')->with('failed', __('default.pages.profile.profile_activate_error'));
        }

        return $next($request);
    }
}
