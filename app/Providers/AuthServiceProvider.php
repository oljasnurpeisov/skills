<?php

namespace App\Providers;

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\RedirectResponse;
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

        $enbekPassport = new EnbekPassport();
        $enbekPassport->init([
            'appName' => config('auth.passportAppName'),
            'accessKey' => config('auth.passportAccessKey'),
        ]);

        if ($enbekPassport->auth()) {
            return redirect((new LoginController())->redirectTo());
        }
    }
}
