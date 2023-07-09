<?php

namespace App\Providers;

use App\Events\OrderStatusUpdated;
use App\Events\PaymentStatusUpdated;
use App\Listeners\SendEmailByOrderStatus;
use App\Listeners\SendEmailNotification;
use App\Listeners\SendSmsNotification;
use App\Listeners\UpdateLogByOrderStatus;
use App\Listeners\UpdateLogByPaymentStatus;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event; 


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // Registered::class => [
        //     SendEmailVerificationNotification::class,
        // ],
        OrderStatusUpdated::class => [
            UpdateLogByOrderStatus::class,
            SendEmailByOrderStatus::class
        ],
        PaymentStatusUpdated::class => [
            UpdateLogByPaymentStatus::class,
            SendSmsNotification::class,
            SendEmailNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
