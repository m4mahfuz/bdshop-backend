<?php

namespace App\Repositories;

use Illuminate\Support\ServiceProvider;

class BackendServiceProvider extends ServiceProvider
{
    public function register()
    {
        // $this->app->bind(
        //     'App\Repositories\Sms\SmsInterface',
        //     //'App\Repositories\Sms\NexmoSms'
        //     'App\Repositories\Sms\AlphaSms'
        // );
        $this->app->bind(
            'App\Repositories\Payment\PaymentInterface',
            'App\Repositories\Payment\Sslcommerz\Sslcommerz'
        );
    }

    public function boot()
    {
        //
    }
}