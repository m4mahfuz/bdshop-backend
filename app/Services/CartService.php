<?php

namespace App\Services;

use App\Http\Requests\StoreCartRequest;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CartService 
{
	public function add(StoreCartRequest $request)
	{
		// $session_id = $this->generateSessionIdIfNotExists();
		
		$item = $this->isItemExistsInCart($request);
		
		if($item !== false) {

			$item = $this->updateExisting($item, $request);

		} else {						
			$item = DB::transaction(function() use($request) {

				$item = Cart::create([
		            // 'session_id' => $session_id,
		            'product_id' => $request->getProductId(),
		            'user_id' => $request->getUserId(), //Auth::user()->id,
					'quantity' => $request->getQuantity()
				]);
				
				// Session::put(['total' => $this->getCartTotal($request)]);

				return $item;
			});
		}
		
		// $item->load('product.inventory.discount');
		// $item->load('product:id,name,slug.featuredImage');

		return $item;		 
	}

	public function update(StoreCartRequest $request, Cart $cart)
	{
		// $product->fill([
		// 	'name' => $request->getName(),
  //           'description' => $request->getDescription(),
  //           'sku' => $request->getSku(),
  //           'category_id' => $request->getCategoryId(),
		// ]);

		// $product->save();
		// $product->fresh();
		// $product->load('category');
		// $product->load('inventory.discount');

		// return $product;
	}

	// public function generateSessionIdIfNotExists()
	// {
	// 	$session_id = Session::get('session_id');

	// 	if (empty($session_id)) {
	// 		$session_id = Session::getId();
	// 		Session::put('session_id', $session_id);
	// 	}
	// 	return $session_id;
	// }

	public function isItemExistsInCart(StoreCartRequest $request)
	{
		// if (Auth::check()) {
		// 	$item = Cart::where([
		// 		'product_id' => $request->getProductId(),
		// 		'user_id' => Auth::user()->id
		// 	]);
		// }
		 // else {
		// 	$item = Cart::where([
		// 		'product_id' => $request->getProductId(),
		// 		'session_id' => Session::get('session_id')
		// 	]);
		// }
		$item = Cart::where([
			'product_id' => $request->getProductId(),
			'user_id' => $request->getUserId() //Auth::user()->id
		])->first();

		return $item ?? false; 
	}

	public function updateExisting(Cart $item, $request)
	{
		if( $request->getAction() === 'add' || $request->getAction() === 'increase') {
			$item->increment('quantity');
			return $item;
		}
		
		if ( $item->quantity > 1) {

			$item->decrement('quantity');
		}
		// Session::put(['total' => $this->getCartTotal($request)]);
		// return $item->first();			
		return $item;			
	}

	// public function getCartTotal(StoreCartRequest $request) 
	// {
	// 	$total = Session::get('total');
		
	// 	$total += floatVal($request->getPrice()) * intval($request->getQuantity());

	// 	return number_format($total, 2); 
	// }

}