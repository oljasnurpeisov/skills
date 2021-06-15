<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Libraries\Auth\AuthEnbekPassport;
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
     * @var AuthEnbekPassport
     */
    private $authEnbekPassport;

    /**
     * EnbekPassportController constructor.
     *
     * @param EnbekPassport $passport
     * @param User $user
     * @param AuthService $authService
     * @param AuthEnbekPassport $authEnbekPassport
     */
    public function __construct(EnbekPassport $passport, User $user, AuthService $authService, AuthEnbekPassport $authEnbekPassport)
    {
        $this->user         = $user;
        $this->passport     = $passport;
        $this->authService  = $authService;
        $this->authEnbekPassport = $authEnbekPassport;

//        $this->passport->init([
//            'appName'   => config('auth.passportAppName'),
//            'accessKey' => config('auth.passportAccessKey'),
//        ]);
    }

    /**
     * Login
     */
    public function login()
    {
        $this->authEnbekPassport->init();
//        $passportAuth = $this->passport->auth();
//
//        if ($passportAuth) {
//            $passportUser = $this->passport->user();
//
//            if (!empty($passportUser)) {
//                $this->authService->loginStudentByEmail($passportUser->email);
//            }
//        }
//
        if (Auth::check()) {
            (new AuthStudent($this->passport->user()->token, $this->passport->user()->email, $this->passport->user()->uid))->afterLogin();

            return redirect(url((new LoginController())->redirectTo()));
        } else {
            return redirect(url('/'));
        }
    }
}
