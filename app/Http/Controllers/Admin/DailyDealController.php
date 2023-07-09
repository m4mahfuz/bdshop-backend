<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDealRequest;
use App\Http\Resources\DailyDealResource;
use App\Http\Resources\DailyDealCollection;
use App\Models\DailyDeal;
// use App\Models\Deal;
use App\Services\DealService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DailyDealController extends Controller
{
    private $deal;

    public function __construct(DealService $deal)
    {
        $this->deal = $deal;
    }


    public function index()
    {        
        // $deals = DailyDeal::with([
        //     // 'deal',
        //     // 'product.category',
        //     'product.featuredImage',          
        // ])->get();
        $this->deal->setToSessionDealType('daily');        

        $deals = DailyDeal::with([
            'product.featuredImage',
            // 'product.dailyDeal'
        ])->orderBy('id')->cursorPaginate(10);
                
        // // return [
        // //     'data' => DailyDealResource::collection($deals),
        // // ];    

        return (new DailyDealCollection($deals))->additional(
            [
                'meta' => [
                    'totalDeals' => DailyDeal::count(), //
                ]
            ]
        );
    }

    public function store(StoreDealRequest $request)
    {
        // return $this->deal->add($request);
        return response([
            'data'=> DailyDealResource::make(
                $this->deal->add($request)
            )
        ], Response::HTTP_CREATED);
    }

    // public function update(Request $request, $deal)
    public function update(StoreDealRequest $request, $deal)
    {        
        // $request->validate([
        //     'active' => 'required|boolean',
        //     'amount_type' => 'required|numeric',
        //     'amount' => 'required|integer',
        //     'deal_type' => 'required|string',
        // ]);
        
        return response([
            'data' => DailyDealResource::make($this->deal->update($request, $deal))
        ], Response::HTTP_OK);
    }

    public function destroy(DailyDeal $deal)
    {
        // $deal->deal->delete();
        $deal->delete();

        return response([], Response::HTTP_NO_CONTENT);
    }
}
