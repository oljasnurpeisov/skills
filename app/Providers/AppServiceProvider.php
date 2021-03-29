<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use App\Mail\EmailVerification;
use URL;
use Carbon\Carbon;
use Config;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

//        , 'en'
        $lang = ['ru', 'kk'];
        $currentPath = Request::path();
        if (in_array(Request::segment(1), $lang, true)) {
            $locale = Request::segment(1);
            $uri = substr($currentPath, 2);
        } else {
            $locale = 'ru';
            $uri = $currentPath;
        }
        App::setLocale($locale);

        View::share('uri', $uri);
        View::share('lang', $locale);

        Blade::if ('hasPermission', static function ($permission) {
            /** @var User $user */
            $user = auth()->user();
            return $user and $user->can($permission);
        });

        // Override the email notification for verifying email
        VerifyEmail::toMailUsing(function ($notifiable){
            $verifyUrl = URL::temporarySignedRoute('verification.verify',
                Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );
            return new EmailVerification($verifyUrl, $notifiable);

        });
    }
}
