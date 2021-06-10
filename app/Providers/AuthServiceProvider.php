<?php

namespace App\Providers;

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Libraries\Auth\AuthEnbekPassport;
use Libraries\Auth\EnbekPassport;
use Service\Auth\AuthService;

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
     */
    public function boot()
    {
        $this->registerPolicies();

        // Авторизация через EnbekPassport
        view()->composer('app.layout.default.template', function () {
            $auth = new AuthEnbekPassport();
            $auth->init();
        });
    }
}
