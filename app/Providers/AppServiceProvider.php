<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;

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
//    {
//        $lang = ["ru", "kk", "en"];
//        $currentPath = Request::path();
//        if (in_array(Request::segment(1), $lang)) {
//            $locale = Request::segment(1);
//            $uri = substr($currentPath, 2);
//        } else {
//            $locale = "ru";
//            $uri = $currentPath;
//        }
//
//
//        App::setLocale($locale);
//        View::share("uri", $uri);
//        View::share("lang", $locale);
//        View::share("title", config("app.name"));
//        View::share("meta", "");
//
//        Schema::defaultStringLength(191);
//    }
    {
        Schema::defaultStringLength(191);

        $lang = ['ru', 'kk', 'en'];
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
    }
}
