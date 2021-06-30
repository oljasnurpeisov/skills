<?php

namespace App\Providers;

use App\Models\Contract;
use App\Policies\Contracts\AdminContractPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Libraries\Auth\AuthEnbekPassport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Contract::class => AdminContractPolicy::class,
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
