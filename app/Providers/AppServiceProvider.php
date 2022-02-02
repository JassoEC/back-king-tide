<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        $this->app->singleton('profileImagesPath', function () {
            return 'users/profile_picture';
        });

        $this->app->singleton('filesPath', function () {
            return 'users/files';
        });
    }
}
