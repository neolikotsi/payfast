<?php

namespace NeoLikotsi;


use Illuminate\Support\ServiceProvider;

class PayfastServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('NeoLikotsi\Contracts\PaymentProcessor', 'NeoLikotsi\Payfast');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/payfast.php' => config_path('payfast.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/config/payfast.php', 'payfast'
        );
    }


}