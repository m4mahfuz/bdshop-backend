<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOfferProductRequest;
use App\Models\Offer;
use App\Models\Product;
use App\Services\OfferProductService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OfferProductController extends Controller
{
    private $offerProduct;

    public function __construct(OfferProductService $offerProduct)
    {
        $this->offerProduct = $offerProduct;
    }
    

    public function store(StoreOfferProductRequest $request)
    {
        return response([
            'data' => $this->offerProduct->add($request)
        ], Response::HTTP_OK);
        
    }

    public function updateProduct(Offer $offer, Product $product, Request $request)
    {        
        // return $request;
        return response([
            'data' => $this->offerProduct->update($offer, $product, $request),
        ], Response::HTTP_OK);
    } 

    public function destroyProduct(Offer $offer, Product $product)
    {
        $offer->products()->detach($product->id);

        return response([], Response::HTTP_NO_CONTENT);
        
    }          


}
