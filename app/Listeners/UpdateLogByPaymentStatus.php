<?php

namespace App\Listeners;

use App\Events\PaymentStatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateLogByPaymentStatus
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

    /**
     * Handle the event.
     *
     * @param  \App\Events\PaymentStatusUpdated  $event
     * @return void
     */
    public function handle(PaymentStatusUpdated $event)
    {
        // $event->payment->order->orderLogs->updateStatusTo($event->status, $event->dateTime);        
        $event->payment->order->createLog();
    }
}
