<?php

namespace App\Listeners;

use App\Events\OrderStatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateLogByOrderStatus
{
    public function __construct()
    {
        //
    }
    
    public function handle(OrderStatusUpdated $event)
    {
        // $event->order->orderLogs->updateStatusTo($event->status);
        $event->order->createLog();
        // $event->order->mailToUser();
    }
}
