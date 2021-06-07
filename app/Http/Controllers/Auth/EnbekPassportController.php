<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Libraries\Auth\EnbekPassport;

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
     * EnbekPassportController constructor.
     *
     * @param EnbekPassport $passport
     * @param User $user
     */
    public function __construct(EnbekPassport $passport, User $user)
    {
        $this->user     = $user;
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
        $passportAuth = $this->passport->auth();

        if ($passportAuth) {
            $this->loginByEmail();
        }

        return redirect(url('/'));
    }

    /**
     * Login by email
     */
    protected function loginByEmail()
    {
        $passportUser = $this->passport->user();

        $user = $this->user->whereEmail($passportUser->email)->first();

        if (!empty($user)) {
            Auth::loginUsingId($user->id, TRUE);

            dd($user);
        } else {
            dd("user not found");
        }
    }
}
