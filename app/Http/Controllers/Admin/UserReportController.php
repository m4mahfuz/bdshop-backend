<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserReportService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserReportController extends Controller
{
    private $report;

    public function __construct(UserReportService $report)
    {
        $this->report = $report;
    }

    public function usersRegisteredFor(int $period)
    {
        $data = $this->report->usersRegisteredFor($period);

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
