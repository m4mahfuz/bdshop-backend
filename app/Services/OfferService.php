<?php

namespace App\Services;

use App\Http\Requests\StoreOfferRequest;
use App\Models\Offer;

class OfferService 
{
	public function add(StoreOfferRequest $request)
	{

		$offer = Offer::create([
			'name' => $request->getName(),
			'type' => $request->getType(),
			'amount' => $request->getAmount(),
			'starting' => $request->getStarting(),
			'ending' => $request->getEnding(),
			'active' => $request->getActive()
		]);

		return $offer; 				
	}

	public function update(StoreOfferRequest $request, Offer $offer)
	{		
		// $offer = DB::transaction(function() use($request, $offer) {
			
			$offer->update([
				'name' => $request->getName(),
				'type' => $request->getType(),
				'amount' => $request->getAmount(),
				'starting' => $request->getStarting(),
				'ending' => $request->getEnding(),
				'active' => $request->getActive()
			]);

			return $offer;
		// });
		
		// return $offer;
	}

	
}