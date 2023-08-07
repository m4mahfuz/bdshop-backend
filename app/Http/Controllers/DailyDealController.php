<?php

namespace App\Http\Controllers;

use App\Http\Resources\DailyDealResource;
use App\Models\DailyDeal;
use App\Services\DealService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DailyDealController extends Controller
{
    public function index(DealService $deal)
    {        
        $deal->setToSessionDealType('daily');
        
        $today = date("Y-m-d");
        $deals = DailyDeal::where('active', true)
        ->whereHas('deal', function(Builder $query) use ($today) {
            $query->where('starting', '<=', $today)
                ->where('ending', '>=', $today);
        })
        ->with([
            'product.category',
            'product.featuredImage',          
        ])->get();
        
        return [
            'data' => DailyDealResource::collection($deals),
        ];    
    }
}
