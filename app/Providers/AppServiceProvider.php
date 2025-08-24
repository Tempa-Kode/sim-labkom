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
        // Load helper files
        if (file_exists(app_path('Helpers/JamHelper.php'))) {
            require_once app_path('Helpers/JamHelper.php');
        }
    }
}
