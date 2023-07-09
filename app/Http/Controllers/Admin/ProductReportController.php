<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ProductReportService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductReportController extends Controller
{
    private $report;

    public function __construct(ProductReportService $report)
    {
        $this->report = $report;
    }

    public function byCategory(int $period)
    {

        $data = $this->report->categoryWiseProductsSalesForMonths($period);
      
        return response([
            'data' => $data
        ], Response::HTTP_OK);
        
    }
   
}
