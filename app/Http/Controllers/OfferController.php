<?php

namespace App\Http\Controllers;

// use App\Http\Requests\StoreOfferRequest;
use App\Http\Resources\OfferResource;
use App\Models\Offer;
// use App\Services\OfferService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OfferController extends Controller
{
    // private $offer;

    // public function __construct(Offer $offer)
    // {
    //     // $this->middleware([
    //     //     'auth:admin',
    //     //     'type:super-admin,admin'
    //     // ])->except('index', 'show');

    //     $this->offer = $offer;
    // }

    public function index()
    {        
        return response([
            'data' => OfferResource::collection(Offer::valid()->with([
                'products' => function($query) {
                    $query->wherePivot('active', true);
                }
                // 'product:id,title,description,keywords,owner_id',
            ])->latest()->get()),
        ], Response::HTTP_OK);
    }

    public function show(Offer $offer)
    {        
        if ($offer->isValidOffer()) {
            $offer->load([            
                'products' => function ($query) {
                    $query->wherePivot('active', true)
                            ->where('products.active', true)
                            ->with('featuredImage');
                }
            ]);

            // $offer->products->each(function($product) {
            //     $product->load('featuredImage');
            // });
            
            return response(['data' => OfferResource::make($offer)], Response::HTTP_OK);
        }

        return response(['data' => ''], Response::HTTP_NOT_FOUND);



    }
    
}
