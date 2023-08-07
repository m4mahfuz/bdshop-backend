<?php

namespace App\Http\Controllers;

use App\Http\Resources\WeeklyDealResource;
use App\Models\WeeklyDeal;
use App\Services\DealService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WeeklyDealController extends Controller
{
    public function index(DealService $deal)
    {        
        $deal->setToSessionDealType('weekly');

        $today = date("Y-m-d");
        $deals = WeeklyDeal::where('active', true)
        ->whereHas('deal', function(Builder $query) use ($today) {
            $query->where('starting', '<=', $today)
                ->where('ending', '>=', $today);
        })
        ->with([
            'product.category',
            'product.featuredImage',          
        ])->get();
                
        return [
            'data' => WeeklyDealResource::collection($deals),
        ];    
    }
}
