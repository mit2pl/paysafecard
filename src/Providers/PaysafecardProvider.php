<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PaysafecardProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../Config/paysafecard.php' => config_path('paysafecard.php'),
        ]);
    }
}
