<?php

namespace App\Providers;

use App\Models\Course;
use App\Observers\CourseObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
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
        /**
         * Observers
         */
        Course::observe(CourseObserver::class);
    }
}
