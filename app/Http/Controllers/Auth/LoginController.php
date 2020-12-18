<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');

//        $this->redirectTo = '/' . app()->getLocale() . '/profile-author-information';

    }

    public function redirectTo(){

        if (Auth::user()->hasRole('admin')){
            return '/'.app()->getLocale().'/admin';
        }else if (Auth::user()->hasRole('author')){
            return '/' . app()->getLocale() . '/profile-author-information';
        }else if (Auth::user()->hasRole('tech_support')){
            return '/' . app()->getLocale() . '/admin';
        }else{
            return '/';
        }
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:users,email|max:255',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/' . app()->getLocale() . '/');
    }

}
