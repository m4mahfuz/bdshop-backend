<?php

namespace App\Http\Controllers;

use App\Http\Resources\DiscountResource;
use App\Models\Discount;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DiscountController extends Controller
{
    public function index()
    {
        return [ 
            'data' => DiscountResource::collection(
                    Discount::with([
                        'deduct' => function ($query) {
                            // $query->select('id','amount','active','starting','ending','deductable_id')
                            $query->where('active', true);
                    }])->latest()->get()
                )
        ];
    }

    // public function show(Discount $discount)
    // {
        
    //     $discount->load([                     
    //         'products',
    //         'categories'
    //     ]);
        
    //     // $discount->products->each(function($product) {
    //     //     $product->load('featuredImage');
    //     // });

    //     return response(['data' => DiscountResource::make($discount)], Response::HTTP_OK);
    // }

    // public function store(StoreDiscountRequest $request)
    // {

    //     return response([
    //         'data'=> new DiscountResource(
    //             Discount::create($request->validated())
    //         )
    //     ], Response::HTTP_CREATED);
    // }

    // public function destroy(Discount $discount)
    // {
    //     $discount->delete();

    //     return response([], Response::HTTP_NO_CONTENT);
    // }

    // protected function validateRequest(Discount $tag = null) {
    //     return request()->validate([
    //         'name' => [
    //             'required',
    //             'string',
    //             Rule::unique('tags', 'name')->ignore($tag?->id)
    //         ],
    //         'slug' => [
    //             'required',
    //             'string',
    //             Rule::unique('tags', 'slug')->ignore($tag?->id)
    //         ],
    //     ]);
    // }

}