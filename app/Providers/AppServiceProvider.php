<?php

namespace App\Providers;
use Illuminate\Support\Facades\Schema;
use App;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        //   App::bind('path.public', function() {
        //     return base_path().'/';
        // });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Schema::defaultStringLength(191);

    }
}
