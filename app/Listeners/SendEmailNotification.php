<?php

namespace App\Listeners;

use App\Events\PaymentStatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmailNotification
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
     * @param  \App\Events\OrderPaymentStatusUpdated  $event
     * @return void
     */
    public function handle(PaymentStatusUpdated $event)
    {
        // dd($event->payment);
        $event->payment->mailToUser();
    }
}
