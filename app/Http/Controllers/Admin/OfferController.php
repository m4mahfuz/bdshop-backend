<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOfferRequest;
use App\Http\Resources\OfferCollection;
use App\Http\Resources\OfferResource;
use App\Models\Offer;
use App\Services\OfferService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OfferController extends Controller
{
    private $offer;

    public function __construct(OfferService $offer)
    {
        $this->offer = $offer;
    }

    public function index()
    {        
        // return response([
        //     'data' => OfferResource::collection(Offer::with([
        //         'products' 
        //         // 'product:id,title,description,keywords,owner_id',
        //     ])->orderBy('id', 'desc')->paginate(3)),
            
        // ], Response::HTTP_OK);

        // return response([
        //     'data' => OfferResource::collection(Offer::orderBy('id', 'desc')->paginate(3)),            
        // ], Response::HTTP_OK);
        $offers = Offer::orderBy('id', 'desc')->paginate(10);

        return (new OfferCollection($offers))->additional(
            [
                'meta' => [
                    'totalOffers' => Offer::count(), //
                ]
            ]
        );

    }

    public function offersBy(string $type)
    {   
        if ($type === 'all') {
        
            $offers = Offer::orderBy('id', 'desc')->paginate(5);    
        }          

        if ($type === 'regular') {

            $offers = Offer::where('type', 1)
                ->orWhere('type', 2)            
                ->orderBy('id', 'desc')->paginate(5);                
        }

        if ($type === 'bogo') {

            $offers = Offer::where('type', 3)
                ->orderBy('id', 'desc')->paginate(5);                
        }

        if ($type === 'btgo') {
            $offers = Offer::where('type', 4)
                ->orderBy('id', 'desc')->paginate(5);                
        }

        return (new OfferCollection($offers))->additional(
            [
                'meta' => [
                    'totalOffers' => Offer::count(), //
                ]
            ]
        );
    }

    public function show(Offer $offer)
    {
        // if (!$this->user->isAuthorized(['super-admin', 'admin'])) {
            // $this->authorize('manage', $order);
        // }
        
        $offer->load([            
            // 'coupon',
            // 'products:id,unit,unit_quantity',
            // 'products.featuredImage',                        
            // 'products.discount',            
            'products'
        ]);
        $offer->products->each(function($product) {
            // $product->pivot->disputed;
            $product->pivot;
            $product->load('featuredImage');
            // $product->pivot->load('disputed:id,status,reason,order_product_id');
        });

        return response(['data' => OfferResource::make($offer)], Response::HTTP_OK);
    }

    public function store(StoreOfferRequest $request)
    {
        return response([
            'data'=> OfferResource::make(
                $this->offer->add($request)
            )
        ], Response::HTTP_CREATED);
    }

    public function update(StoreOfferRequest $request, Offer $offer)
    {        
        return response([
            'data' => OfferResource::make($this->offer->update($request, $offer))
        ], Response::HTTP_OK);
    }

    
}
