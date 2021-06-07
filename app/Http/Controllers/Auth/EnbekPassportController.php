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
            'appName' => config('passportAppName'),
            'accessKey' => config('passportAccessKey'),
        ]);

        // Получаем сведения о авторизованном пользователе
        $user = $enbekPassport->user();

        $auth = $enbekPassport->auth();

        dump($auth);
    }
}
