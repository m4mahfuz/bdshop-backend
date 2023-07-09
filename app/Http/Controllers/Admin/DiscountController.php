<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDiscountRequest;
use App\Http\Resources\DiscountCollection;
use App\Http\Resources\DiscountResource;
use App\Models\Discount;
use App\Services\DiscountService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DiscountController extends Controller
{
    private $discount;

    public function __construct(DiscountService $discount)
    {
        $this->discount = $discount;
    }


    public function index()
    {
        $discounts = Discount::with([
                        'deduct:id,amount,active,limit,starting,ending,deductable_id'
        ])->orderBy('id', 'desc')->paginate(10);

        return (new DiscountCollection($discounts))->additional(
            [
                'meta' => [
                    'totalDiscounts' => Discount::count(),
                ]
            ]
        );
    }

    public function show(Discount $discount)
    {
        
        $discount->load([                     
            'products',
            'categories'
        ]);
        
        // $discount->products->each(function($product) {
        //     $product->load('featuredImage');
        // });

        return response(['data' => DiscountResource::make($discount)], Response::HTTP_OK);
    }


    public function store(StoreDiscountRequest $request)
    {
        return response([
            'data'=> DiscountResource::make(
                $this->discount->add($request)
            )
        ], Response::HTTP_CREATED);
    }

    public function update(Discount $discount, StoreDiscountRequest $request)
    {
        return response([
            'data'=> DiscountResource::make(
                $this->discount->update($request, $discount)
            )
        ], Response::HTTP_CREATED);
    }

    public function destroy(Discount $discount)
    {
        $discount->delete();

        return response([], Response::HTTP_NO_CONTENT);
    }    

}