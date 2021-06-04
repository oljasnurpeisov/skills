<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
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
     * @return void
     */
    public function boot()
    {
//        $enbekPassport = new EnbekPassport();
//        $enbekPassport->init([
//            'appName' => config('auth.passportAppName'),
//            'accessKey' => config('auth.passportAccessKey'),
//        ]);
//
//        dd($enbekPassport->auth());


        $this->registerPolicies();



        //
    }
}
