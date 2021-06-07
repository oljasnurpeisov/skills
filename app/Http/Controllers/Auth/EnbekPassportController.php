<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Libraries\Auth\EnbekPassport;

class EnbekPassportController extends Controller
{
    public function login()
    {
        $enbekPassport = new EnbekPassport();

        $enbekPassport->init([
            'appName' => config('auth.passportAppName'),
            'accessKey' => config('auth.passportAccessKey'),
        ]);

        // Получаем сведения о авторизованном пользователе
        $user = $enbekPassport->user();

        dump($user);

        $auth = $enbekPassport->auth();

        dump($auth);
    }
}
