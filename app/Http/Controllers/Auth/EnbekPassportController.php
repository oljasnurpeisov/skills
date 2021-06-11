<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Libraries\Auth\AuthStudent;
use Libraries\Auth\EnbekPassport;
use Service\Auth\AuthService;

class EnbekPassportController extends Controller
{
    /**
     * @var EnbekPassport
     */
    private $passport;

    /**
     * @var User
     */
    private $user;

    /**
     * @var AuthService
     */
    private $authService;

    /**
     * EnbekPassportController constructor.
     *
     * @param EnbekPassport $passport
     * @param User $user
     * @param AuthService $authService
     */
    public function __construct(EnbekPassport $passport, User $user, AuthService $authService)
    {
        $this->user         = $user;
        $this->passport     = $passport;
        $this->authService  = $authService;

        $this->passport->init([
            'appName'   => config('auth.passportAppName'),
            'accessKey' => config('auth.passportAccessKey'),
        ]);
    }

    /**
     * Login
     */
    public function login()
    {
        $passportAuth = $this->passport->auth();

        if ($passportAuth) {
            $passportUser = $this->passport->user();

            if (!empty($passportUser)) {
                $this->authService->loginStudentByEmail($passportUser->email);
            }
        }

        if (Auth::check()) {
            (new AuthStudent($this->enbekPassport->user()->token, $this->enbekPassport->user()->email, $this->enbekPassport->user()->uid))->afterLogin();

            return redirect(url((new LoginController())->redirectTo()));
        } else {
            return redirect(url('/'));
        }
    }
}
