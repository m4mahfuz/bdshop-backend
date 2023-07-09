<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OrderReportService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderReportController extends Controller
{
    private $report;

    public function __construct(OrderReportService $report)
    {
        $this->report = $report;
    }

    public function ordersReceivedFor(int $period)
    {
        $data = $this->report->ordersReceivedFor($period);

        return response([
            'data' => $data
        ], Response::HTTP_OK);
        
    }

    public function ordersReceivedHourelyFor(int $period)
    {
        $data = $this->report->hourelyOrdersReceivedForLast($period);

        return response([
            'data' => $data
        ], Response::HTTP_OK);
        
    }
            

    // public function orderLogsBy(Order $order)
    // {
    //     return response([
    //         'data' => OrderLogResource::collection($order->orderLogs)
    //     ], Response::HTTP_OK);
    // }    

}
