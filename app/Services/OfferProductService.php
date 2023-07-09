<?php

namespace App\Services;

use App\Http\Requests\StoreOfferProductRequest;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfferProductService 
{
	public function add(StoreOfferProductRequest $request)
	{
		$offer = DB::transaction(function() use($request) {
			$offer = Offer::find($request->getOfferId());


			foreach ($request->getProducts() as $product) {

				$product = Product::find($product['id']);

				$offer->products()->syncWithoutDetaching([
					$product->id => [
						'price' => $product->price,
						'active' => true
					]
				]); 

				$product->deactivateOtherOfferForThisProduct($offer);
			}
		});
		 

		return $offer; 				
	}

	public function update(Offer $offer, Product $product, Request $request)
	{		
           // return $request;
		$offer = DB::transaction(function() use($offer, $product, $request) {
			
            // $offer->pivot->active = $request->active;
            // $offer->pivot->save();
            $offer->products()->wherePivot('product_id', $product->id)->update(['offer_product.active' => $request->active]);

            if ($request->active) {            	

	            // Product::find($request->product)->deactivateOtherOfferForThisProduct($offer);
	            $product->deactivateOtherOfferForThisProduct($offer);
            }

			return $offer;
		});
		
		return $offer;
	}

	
}