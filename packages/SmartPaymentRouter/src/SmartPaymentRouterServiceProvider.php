<?php

namespace blinqpay\SmartPaymentRouter;

use Illuminate\Support\ServiceProvider;

class SmartPaymentRouterServiceProvider extends ServiceProvider
{
    public function register()
    {
        // bindings or services
        $this->app->singleton(PaymentRouter::class, function ($app) {
            return new PaymentRouter();
        });
    }

    public function boot()
    {
        // Load routes, migrations, etc.
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->publishes([
            __DIR__ . '/config/smartpaymentrouter.php' => config_path('smartpaymentrouter.php'),
        ]);
    }
}
