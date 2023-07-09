<?php

namespace App\Services;

use App\Events\OrderStatusUpdated;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Shipping;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class OrderService 
{
	public function add(StoreOrderRequest $request)
	{
		
		$order = DB::transaction(function() use($request) {

			// return ['rc' =>$request->getCouponCode()
			// 	, 'sc'=>session('couponCode'), 'cdAmount'=>session('couponDiscountdAmount')];

			// if ($couponCode !== null && session('couponCode') === $couponCode) {
			// 	$couponDiscountedAmount = session('couponDiscountdAmount');
			// }

			$address = Address::find($request->getAddressId());

			$shippingCharge = round(Shipping::chargeFor($address?->city, $request->getShippingType()));
			
			// session(['shippingCharge'=>$shippingCharge]);

			$order = Order::create([
				'uuid' => Str::orderedUuid()->getHex(),
				'user_id' => Auth::user()->id,
				// 'coupon_code' => $request->getCouponCode(), 
				// 'coupon_amount' => $request->getCouponDiscountedAmount(),
				'payment_method' => $request->getPaymentMethod(),
				'shipping_id' => Shipping::byCity($address?->city)->id,				
				'shipping_type' => $request->getShippingType(),
				'shipping_charge' => $shippingCharge,
				'status' => 'Received',
			]);
			
			// OrderStatusUpdated::dispatch($order);

			// $couponCode = $request->getCouponCode();
			$couponDiscountedAmount = 0;

			if ($request->getCouponCode() !== null) {

				$couponDiscountedAmount = session('couponDiscountdAmount');			

				$orderCoupon = $order->coupon()->create([
					'code' => $request->getCouponCode(),//$request->getCouponCode(), 
					'amount' => $couponDiscountedAmount,
				]);
			}

			$shippingAddress = $order->shippingAddress()->create([
				'user_id' => $address->user_id,
				'name' => $address->name,
				'address_line' => $address->address_line,
				'phone' => $address->phone,
				'city' => $address->city,
				'postal_code' => $address->postal_code,

			]);

			$userCartItems = Cart::items();

			$product = new ProductPriceService;	        
			$total = 0;
			// $offer = false;
			// $dealOriginalPrice = null;
			$discountType = null;
			$additonalQty = null;
			$price = 0;
			$quantity = 0;

			foreach ($userCartItems as $item) {
				
				$quantity = $item->quantity;

				// $price = $item->product->isPriceAvailableFor('offer');
				$offer = $item->product->activeOffer();
				// $price = $offer?->offerPriceBasedOnProduct($item->product->price);
				$price = $item->product->discountedPrice()?? $item->product->price;
				
				// if($price) {
				if($offer) {
					// if ($item->product->offers->type === Offer::TYPE_PERCENTAGE) {
					if ($offer->type === Offer::TYPE_PERCENTAGE) {
						
						// $order->additional()->create([
						// 	'discount_type' => 'Percentage',
						// 	'discounted_price' => $price
						// ]);
						$discountType = 'Percentage';
						$additonalQty = null;
						$item->product->updateInventory($quantity);
					}

					// if ($item->product->offers->type === Offer::TYPE_FIXED) {
					if ($offer->type === Offer::TYPE_FIXED) {
						
						// $order->additional()->create([
						// 	'discount_type' => 'Fixed Amount',
						// 	'discounted_price' => $price
						// ]);

						$discountType = 'Fixed Amount';
						$additonalQty = null;
						$item->product->updateInventory($quantity);
					}

					// if ($item->product->offers->type === Offer::TYPE_BOGO) {
					if ($offer->type === Offer::TYPE_BOGO) {
						
						// $order->additional()->create([
						// 	'discount_type' => 'Buy 1 Get 1',
						// 	'additonal_qty' => $quantity
						// ]);
						$discountType = 'Buy 1 Get 1';
						$additonalQty = $quantity;
						$item->product->updateInventory($quantity*2);
						// $total = $total + ((int) $price * $quantity);
					}

					if ($offer->type === Offer::TYPE_BTGO) {
						// $additonal_qty = $quantity/2;
						if ($quantity > 1) {
							
							// $order->additional()->create([
							// 	'discount_type' => 'Buy 2 Get 1',
							// 	'additonal_qty' => $quantity/2
							// ]);

							$discountType = 'Buy 2 Get 1';
							$additonalQty = $quantity/2;

							$modQty = $quantity + $quantity/2;

							$item->product->updateInventory($modQty);
							// $total = $total + ((int) $price * $modQuantity);
						}
						else {
							$discountType = null;
							$additonalQty = null;
							$item->product->updateInventory($quantity);
						}
					}
				} else 
				{
					$weeklyDealProduct = $item->product->getActiveDealType('weeklyDeal');

					if ($weeklyDealProduct) {
		                // $dealOriginalPrice = $item->product->weeklyDeal->price;
		                $price = $item->product->weeklyDeal?->deal->getPrice();
		            }

		            $dailyDealProduct = $item->product->getActiveDealType('dailyDeal');

					if ($dailyDealProduct) {
		                // $dealOriginalPrice = $item->product->dailyDeal->price;
		                $price= $item->product->dailyDeal?->deal->getPrice();
		            }

					// $price = $this->isPriceAvailableFor($item->product, 'deal');
				
					if (is_null($price)) {
						$product->initialize($item->product);
						// $price = $product->price() ?? $item->product->price;
						$price = $product->price(); // ?? $item->product->price;
						if ($price) {
							// $order->additional()->create([
							// 	'discount_type' => 'Percentage',
							// 	'discounted_price' => $price
							// ]);
							$discountType = 'Percentage';
							$additonalQty = null;
						} else {
							$price = $item->product->price;
							$discountType = null;
							$additonalQty = null;
						}
					}

				// $quantity = $item->quantity;
					$item->product->updateInventory($quantity);

					// $total = $total + ((int) $price * $quantity);
				}

				$total = $total + ((int) $price * $quantity);
				// $order->products()->attach($item->product_id, [
				$order->products()->syncWithoutDetaching([$item->product_id => [
					'user_id' => $item->user_id,
					'name' => $item->product->name,
					// 'price' => $dealOriginalPrice?? $item->product->price, // not ok, regular / deal price//
					'price' => $item->product->price, // not ok, regular / deal price//
					'discounted_price' => $item->product->discountedPrice(),//offer/deal/discoun price $product->price(),
					'discount_type' => $discountType,
					'quantity' => $quantity,
					'additional_quantity' => $additonalQty,
				]]);				
			}
						
			//netTotalCalculation
			$netTotal = round(($total + $shippingCharge) - $couponDiscountedAmount);
			
			session(['netTotalTemp' => $netTotal]);

			if ( (int) $netTotal !== (int) $request->getTotalPrice() ) {
				throw new \Exception('Something went wrong!');
			}
			
			// update order
			$order->update([
				'total' => round($total),
				'net_total' =>$netTotal,
			]);

			//Payment
			if ($order->payment_method === Payment::PAYMENT_METHOD_COD) {				
				// $order->savePayment();
				Payment::for($order);
			}

			OrderStatusUpdated::dispatch($order);
			
			// empty the cart
			Cart::empty();

			return $order;

		});

		// OrderStatusUpdated::dispatch($order);

		session([
			'orderId' => $order->id,
			'total' => $order->total,
			'netTotal' => $order->net_total,
			// 'shippingCharge' => $shippingCharge
		]);

		// $order->load(['coupon', 'shippingAddress']);

		return $order; 				
		// if ($request->paymentMethod() === 'COD') {
		// 	return $this->success();
		// } 

		// return [
		// 	'url' => '', //$url,
		// 	'message' => 'Something went wrong!'//$msg
		// ];
	}

	// public function isPriceAvailableFor(Product $product, string $type)
	// {
	// 	return $type === 'deal' ? $product->dealPrice() : $product->offerPrice();
	// }

	public function success($value='')
	{
			// empty the cart
			// Cart::empty();
			// $url = '/thanks';
			// $msg = 'Order placed successfully.'
			return [
				'url' => '/thanks',
				'message' => 'Order placed successfully.'
			];
	}	
	
}