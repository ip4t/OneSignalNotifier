<?php

namespace Nourallah\OneSignalNotifier\Facades;

use Illuminate\Support\Facades\Facade;

class OneSignalNotifier extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'onesignal-notifier';
    }
}