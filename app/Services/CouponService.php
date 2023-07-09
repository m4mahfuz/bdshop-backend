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

	/***** discount *****/


  //   public function checkValidityOf($coupon, $totalPrice)
  //   {
  //   	$discountAmount = $this->calculateDiscountOn($coupon, $totalPrice);

  //   	$minSpending = $this->isValidMinimumSpending($coupon->minimum_spening, $totalPrice);

 	// 	if (!is_null($coupon->deduct->limit)) {
    		
	 //    	$limitValidity = $this->isDiscountAmountWithinLimit($discountAmount, $coupon->deduct->limit);

	 //    	if ($limitValidity == false ) {
	 //    		$discountAmount = $this->$setLimitAsDiscount($discount->limit);
	 //    	}
  //   	}

  //   	$startingDate = $this->isValid('starting', $coupon->starting);
    	
  //   	$endingDate = $this->isValid('ending', $coupon->ending);

  //   	$validity = $minSpending && $startingDate && $endingDate;

  //   	$arr = [
  //   		'isValid' => $validity,
  //   		'discount_amount' => $discountAmount,
  //           'error' => $this->error,
  //   	];

  //   	return $arr;
  //   }

 
  //   public function calculateDiscountOn($coupon, $totalPrice)
  //   {
  //   	if ($coupon->amount_type === Coupon::AMOUNT_TYPE_PERCENTAGE) {

	 //    	return $totalPrice*($coupon->deduct->amount*0.01);
  //   	}
		// return $coupon->deduct->amount;
  //   }

  //   public function isValidMinimumSpending($minimum_spening, $totalPrice)
  //   {
  //   	if ( is_null($minimum_spening) ) {
  //   		return true;
  //   	}

  //   	if ($totalPrice >= $minimum_spening) {
  //   		return true;
  //   	}

  //       $this->error = $minimum_spening.'৳ minimum spending required';
        
  //   	return false;
  //   }

  //   public function isDiscountAmountWithinLimit($discountAmount, $limit)
  //   {
  //   	return ($discountAmount <= $limit) ? true : false;  
  //   }

  //   public function setLimitAsDiscount($limit)
  //   {
  //   	return $limit;
  //   }

    
  //  public function isValid(String $status, $date)
  //   {
  //   	if (is_null($date)) {
  //   		return false;
  //   	}

  //   	$today = date("Y-m-d");

  //   	if ($status == 'starting') {
  //   		$date = date("Y-m-d", strtotime($date));
            
  //           if ($today >= $date) {
  //               return true;
  //           } 

  //           $this->error = "Offer/ Promotion is not strated yet!";
  //           return false;
  //   	}

  //   	$date = date("Y-m-d", strtotime($date));

		// // return ($today <= $date) ? true : false;
  //       if ($today <= $date) {
  //           return true;
  //       } 

  //       $this->error = 'Sorry! Offer/ Promotion is over.';
  //       return false;
  //   }

	/**** end */
	
}