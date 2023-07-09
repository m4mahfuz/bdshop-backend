<?php

namespace App\Http\Controllers;

use App\Http\Resources\DailyDealResource;
use App\Models\DailyDeal;
use Illuminate\Http\Request;

class ProductDealController extends Controller
{
    public function index()
    {        
        $deals = Deal::
            ->when(request('type'), fn($query) => $query->where('type', request('type')))
            ->with([
                'product.categories',
                // 'product.tags',
                // 'product.discount.deduct'
            ])->get();
                    
        return [
            'data' => DealResource::collection($deals),
        ];    
    }
}
