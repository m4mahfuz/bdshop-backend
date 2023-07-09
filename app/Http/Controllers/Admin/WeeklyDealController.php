<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDealRequest;
use App\Http\Resources\WeeklyDealResource;
use App\Http\Resources\WeeklyDealCollection;
use App\Models\WeeklyDeal;
// use App\Models\Deal;
use App\Services\DealService;
// use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WeeklyDealController extends Controller
{
    private $deal;

    public function __construct(DealService $deal)
    {
        $this->deal = $deal;
    }


    public function index()
    {     
        $this->deal->setToSessionDealType('weekly');

        $deals = WeeklyDeal::with([
            'product.featuredImage',
        ])->orderBy('id')->cursorPaginate(10);
                
        return (new WeeklyDealCollection($deals))->additional(
            [
                'meta' => [
                    'totalDeals' => WeeklyDeal::count(), //
                ]
            ]
        );
    }

    public function store(StoreDealRequest $request)
    {
        return $this->deal->add($request);

        return response([
            'data'=> WeeklyDealResource::make(
                $this->deal->add($request)
            )
        ], Response::HTTP_CREATED);
    }

    public function update(StoreDealRequest $request, $deal)
    {        
        return response([
            'data' => WeeklyDealResource::make($this->deal->update($request, $deal))
        ], Response::HTTP_OK);
    }

    public function destroy(WeeklyDeal $deal)
    {
        $deal->delete();

        return response([], Response::HTTP_NO_CONTENT);
    }
}
