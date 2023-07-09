<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class OrderReportService 
{

    
    public function ordersReceivedFor($numberOfMonths)
    {
        // $numberOfMonths = 12;
        $currentMonth = Carbon::now()->month;
        
        $numberOfMonths = $numberOfMonths > $currentMonth ? $currentMonth : $numberOfMonths;

        $date = Carbon::now()->subMonth($numberOfMonths-1);

        $month= $date->month;

        $users = Order::select(
            DB::raw("(COUNT(*)) as count"),
            DB::raw("MONTHNAME(created_at) as month_name")
        )
        ->whereYear('created_at', '>=', $date)
        ->groupBy('month_name')
        ->get();

        $counts = [];
        $months = [];

        for ($i=$month; $i <= $currentMonth; $i++) { 

            $found = false;

            $month = date("F", strtotime(date("Y")."-".$i."-01"));

            foreach ($users as $user) {
                if ($user->month_name === $month) {
                    $counts[] = $user->count;
                    $months[] = $user->month_name;
                    $found = true;
                }
            }
    
            if (!$found) {
                $counts[] = 0;
                $months[] = $month;
            }
        }

        return [
            'counts' => $counts,
            'months' => $months,
        ];
    }


    public function hourelyOrdersReceivedForLast(int $hours=24)
    {
        $ordersFound = Order::selectRaw("DATE_FORMAT(created_at, '%h %p') as hour, COUNT(*) as total_orders")
        ->where('created_at', '>', Carbon::now()->subHours($hours))
        ->groupByRaw("DATE_FORMAT(created_at, '%h %p')")
        ->get();

        $orders = [];
        $hours = [];

        foreach ($ordersFound as $order) {
            $orders[] = $order->total_orders;
            $hours[] = $order->hour;
        }

        return [
            'orders' => $orders,
            'hours' => $hours,
        ];
    }
	
}