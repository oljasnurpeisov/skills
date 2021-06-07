<?php

namespace App\Providers;

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Libraries\Auth\EnbekPassport;

//use Libraries\Auth\EnbekPassport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return RedirectResponse
     */
    public function boot()
    {
        $this->registerPolicies();

        // Авторизация через EnbekPassport
        view()->composer('*', function () {
            $enbekPassport = new EnbekPassport();
            $enbekPassport->init([
                'appName' => config('auth.passportAppName'),
                'accessKey' => config('auth.passportAccessKey'),
            ]);

            dump($enbekPassport->auth());

            if ($enbekPassport->auth()) {
                $passportUser = $enbekPassport->user();

                $user = $this->user->whereEmail($passportUser->email)->first();

                if (!empty($user)) {
                    Auth::login($user, true);
                } else {
                    dd("user not found");
                }

                return redirect((new LoginController())->redirectTo());
            }
        });

    }
}
