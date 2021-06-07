<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Libraries\Auth\EnbekPassport;

class EnbekPassportController extends Controller
{
    /**
     * @var void
     */
    private $passport;

    public function __construct()
    {
        $enbekPassport = new EnbekPassport();

        $this->passport = $enbekPassport->init([
            'appName' => config('auth.passportAppName'),
            'accessKey' => config('auth.passportAccessKey'),
        ]);
    }

    public function login()
    {


        // Получаем сведения о авторизованном пользователе
        $user = $this->passport->user();

        dump($user);

        $auth = $this->passport->auth();

        dump($auth);
    }
}
