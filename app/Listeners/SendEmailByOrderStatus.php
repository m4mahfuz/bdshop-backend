<?php

namespace App\Listeners;

use App\Events\OrderStatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmailByOrderStatus
{
    public function __construct()
    {
        //
    }
    
    public function handle(OrderStatusUpdated $event)
    {        
        $event->order->mailToUser();
    }
}
