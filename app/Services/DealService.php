<?php

namespace App\Services;

use App\Http\Requests\StoreDealRequest;
use App\Models\DailyDeal;
use App\Models\WeeklyDeal;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class DealService 
{

	public function add(StoreDealRequest $request)
	{

		$deal = DB::transaction(function() use($request) {

			if ($request->getDealType() === 'daily') {
				$dealable = DailyDeal::create([
					'product_id' => $request->getProductId(),
					// 'price' => $request->getProductPrice(),
					'active' => $request->getActive()
				]);
			} 
			
			if ($request->getDealType() === 'weekly') {
				$dealable = WeeklyDeal::create([
					'product_id' => $request->getProductId(),
					// 'price' => $request->getProductPrice(),
					'active' => $request->getActive()
				]);
			}

			$deal = $dealable->deal()->create([
				'amount_type' => $request->getAmountType(),
				'amount' => $request->getAmount(),
				'starting' =>  $request->getStarting(),
				'ending' =>  $request->getEnding(),
			]);			

			return $deal;
		});
		return $deal->dealable; 
		// return $deal->load('dealable.product');		 
	}

	public function update(StoreDealRequest $request, $id)
	{		

		$deal = DB::transaction(function() use($request, $id) {

			if ($request->input('deal_type') === 'daily') {
				$deal = DailyDeal::find($id);
			} 
			
			if ($request->input('deal_type') === 'weekly') {
				$deal = WeeklyDeal::find($id);
			} 

			$deal->update([
				'active' => $request->input('active'),
			]);
			
			$deal->deal()->update([
				'amount_type' => $request->input('amount_type'),
				'amount' => $request->input('amount'),
				'starting' => $request->getStarting(),
				'ending' => $request->getEnding(),
			]);

			return $deal;
		});

		return $deal;		 
	}	
	
	public function setToSessionDealType(string $dealType)
	{
		if (session()->has('dealType')) {
			session()->forget('dealType');
		}
        session(['dealType' => $dealType]);
	}
}