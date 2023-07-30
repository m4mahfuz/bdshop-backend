<?php

namespace App\Services;

use App\Models\Coupon;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreCouponRequest;
use Symfony\Component\HttpFoundation\Response;

class CouponService 
{
	protected $error;

	public function add(StoreCouponRequest $request)
	{
		$coupon = DB::transaction(function() use($request) {

			$coupon = Coupon::create([
				'code' => $request->getCode(),
				'categories' => $request->getCategoryIds(),
				// 'users' =>  $request->getUsersIds(),
				'usage' =>  $request->getUsage(),
				'amount_type' => $request->getAmountType(),
				'minimum_spending' => $request->getMinimumSpending() 
			]);
			
			$deduct = $coupon->deduct()->create([
				'amount' => $request->getAmount(),
				'limit' => $request->getLimit(),
				'starting' => $request->getStartingDate(),
				'ending' => $request->getEndingDate(),
			]);

			return $coupon;
		});

		return $coupon;		 
	}

	public function update(StoreCouponRequest $request, Coupon $coupon)
	{		

		$coupon = DB::transaction(function() use($request, $coupon) {

			$coupon->update([
				'categories' => $request->getCategoryIds(),
				'usage' =>  $request->getUsage(),
			]);
			
			$deduct = $coupon->deduct()->update([
				// 'amount' => $request->getAmount(),
				'active' => $request->getActive(),
				'limit' => $request->getLimit(),
				'starting' => $request->getStartingDate(),
				'ending' => $request->getEndingDate(),
			]);

			return $coupon;
		});

		return $coupon;		 
	}		
}