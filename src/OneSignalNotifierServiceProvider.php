<?php

namespace Nourallah\OneSignalNotifier;

use Illuminate\Support\ServiceProvider;



class OneSignalNotifierServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/onesignal.php', 'onesignal');

        $this->app->singleton('onesignal-notifier', function () {
            return new OneSignalNotifier();
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/onesignal.php' => config_path('onesignal.php'),
        ], 'config');
    }
}
