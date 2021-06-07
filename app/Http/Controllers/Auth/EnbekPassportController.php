<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Dialog;
use App\Models\PayInformation;
use App\Models\User;
use App\Models\UserInformation;
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

        $this->register((object) ['email' => 'test@testtest.test3']);

        if (Auth::check()) {
            return redirect(url((new LoginController())->redirectTo()));
        }
    }

    /**
     * Login by email
     */
    protected function loginByEmail()
    {
        $passportUser = $this->passport->user();

        if (empty($passportUser)) return;

        $user = $this->user->whereEmail($passportUser->email)->first();


        if (!empty($user)) {
            Auth::login($user, true);
        } else {
            $this->register($passportUser);
        }
    }

    protected function register($passportUser)
    {
        $user = $this->user->create(['email' => $passportUser->email]);

        $user_information = new UserInformation;
        $user_information->user_id = $user->id;
        $user_information->save();

        $user_pay_information = new PayInformation;
        $user_pay_information->user_id = $user->id;
        $user_pay_information->save();

        $user->roles()->sync([4]);

        // Создание диалога с тех.поддержкой
        $tech_support = User::whereHas('roles', function ($q) {
            $q->where('slug', '=', 'tech_support');
        })->first();

        $dialog = new Dialog;
        $dialog->save();

        $dialog->members()->sync([$user->id, $tech_support->id]);

        Auth::login($user, true);
    }
}
