<?php

namespace App\Services;

use App\Events\OrderStatusUpdated;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Address;
use App\Models\Cart;
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

			foreach ($userCartItems as $item) {

				$price = $this->isDealAvailableFor($item->product);
				
				if (is_null($price)) {
					$product->initialize($item->product);
					$price = $product->price() ?? $item->product->price;
				}

				// $product->initialize($item->product);

				// $price = $product->price() ?? $item->product->price;

				$quantity = $item->quantity;

				$total = $total + ((int) $price * $quantity);
				// $order->products()->syncWithoutDetaching($item->product_id, [
				$order->products()->attach($item->product_id, [
					'user_id' => $item->user_id,
					'name' => $item->product->name,
					'price' => $item->product->price,
					'discounted_price' => $product->price(),
					'quantity' => $quantity
				]);				
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

	public function isDealAvailableFor(Product $product)
	{
		return $item->product->dealPrice();
	}

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