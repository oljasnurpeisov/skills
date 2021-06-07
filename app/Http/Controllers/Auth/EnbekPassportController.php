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

    /**
     * EnbekPassportController constructor.
     *
     * @param EnbekPassport $passport
     */
    public function __construct(EnbekPassport $passport)
    {
        $this->passport = $passport;
        $this->passport->init([
            'appName' => config('auth.passportAppName'),
            'accessKey' => config('auth.passportAccessKey'),
        ]);
    }

    /**
     * Login
     */
    public function login()
    {
        // Получаем сведения о авторизованном пользователе
        $user = $this->passport->user();

        dump($user);

        $auth = $this->passport->auth();

        dump($auth);
    }
}
