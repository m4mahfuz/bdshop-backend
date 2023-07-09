<?php

namespace App\Listeners;

use App\Events\PaymentStatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSmsNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    
    public function handle(PaymentStatusUpdated $event)
    {
        //
    }
}
