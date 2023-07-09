<?php

namespace App\Services;

use App\Models\Discount;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreDiscountRequest;

class DiscountService 
{
	public function add(StoreDiscountRequest $request)
	{
		$discount = DB::transaction(function() use($request) {

			$discount = Discount::create([
				'name' => $request->getName(),
				'description' => $request->getDescription()
			]);

			$discount->deduct()->create([
				'amount' => $request->getAmount(),
				'starting' => $request->getStarting(),
				'ending' => $request->getEnding(),
				'active' => $request->getActive(),
				'limit' => $request->getLimit(),
			]);

			return $discount; 				
		});

		return $discount; 				
	}

	public function update(StoreDiscountRequest $request, Discount $discount)
	{		
		$discount = DB::transaction(function() use($request, $discount) {

			$discount->update([
				'name' => $request->getName(),
				'description' => $request->getDescription()
			]);
			
			$discount->deduct()->update([
				'amount' => $request->getAmount(),
				'limit' => $request->getLimit(),
				'starting' => $request->getStarting(),
				'ending' => $request->getEnding(),
			]);

			return $discount;
		});
		
		return $discount;
	}

	
}