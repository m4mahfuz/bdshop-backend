<?php

namespace App\Services;

use App\Http\Requests\StoreProductRequest;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Deduct;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\Product;
use App\Services\ProductPriceService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ProductService 
{	
	protected $error;
	
	public function add(StoreProductRequest $request)
	{
		
		$product = DB::transaction(function() use($request) {

			$inventory = Inventory::create([
				'sku' =>$request->getSku(),
				'quantity' => $request->getQuantity()
			]);

			$product = $inventory->product()->create([
                'name' => $request->getName(),
                'slug' => $request->getSlug(),
                'description' => $request->getDescription(),
				'price' => $request->getPrice(),
				'unit' => $request->getUnit(),
				'unit_quantity' => $request->getUnitQuantity(),
				'category_id' => $request->getCategoryId(),				
				'discount_id' => $request->getDiscountId(),				
				'active' => $request->getActive(),
			]);

			 // //storing in disk
    //         $path = $request->getImage()->storePublicly('/images/products');

            //storing path in db images table
			$image = $product->images()->create([
				// 'path' => $request->getImagePath()
				'name' => $request->getImageName()
			]);

			// make the image as featured
			$image->owner()->update([
	            'featured_image_id' => $image->id
	        ]);

			// $product->categories()->attach($request->getCategoryIds());		

			//tags
			if ($request->getTags() !==null) {
				$product->tags()->attach($request->getTags());
			}

			// //meta
			// $product->meta()->create([
            //     'title' => $request->getMetaTitle(),
            //     'description' => $request->getMetaDescription(),
            //     'keywords' => $request->getMetaKeywords(),
			// ]);
			$this->createMetaForThe($product, $request);

			return $product;
		});
		
		return $product;
		
		// return $this->loadRelationOf($product);
	}

	public function update(StoreProductRequest $request, Product $product)
	{		
		$product = DB::transaction(function() use($request, $product) {
			
			$product->update([
				'name' => $request->getName(),
				'slug' => $request->getSlug(),
	            'description' => $request->getDescription(),
				'price' => $request->getPrice(),
				'unit' => $request->getUnit(),
				'unit_quantity' => $request->getUnitQuantity(),
				'category_id' => $request->getCategoryId(),				
				'active' => $request->getActive(),
			]);

			//update inventory
			$product->inventory()->update([
				'sku' =>$request->getSku(),
				'quantity' => $request->getQuantity()
			]);	

			// $product->categories()->syncWithoutDetaching($request->getCategoryIds());
			$this->updateMetaForThe($product, $request);

			return $product;
		});
		
		return $product;
		// return $this->loadRelationOf($product);
	}

	public function loadRelationOf(Product $product)
	{
		return $product->load([
			// 'categories:id,name,slug,product_id', 
			// 'categories:id,name,slug', 
			// 'category:id,name,slug,parrent_id,discount_id', 
			// 'meta:id,title,description,keywords,owner_id',
			'meta:id,title,description,keywords',
			'discount.deduct',
			'inventory:id,sku,quantity',
			'tags'
		]);
	}

	public function createMetaForThe(Product $product, StoreProductRequest $request)
	{
		//meta
			return $product->meta()->create([
                'title' => $request->getMetaTitle(),
                'description' => $request->getMetaDescription(),
                'keywords' => $request->getMetaKeywords(),
			]);
	}

	public function updateMetaForThe(Product $product, StoreProductRequest $request)
	{
		//meta
			return $product->meta()->update([
                'title' => $request->getMetaTitle(),
                'description' => $request->getMetaDescription(),
                'keywords' => $request->getMetaKeywords(),
			]);
	}


	public function toggleWishlist(Product $product)
	{
		$wishlistCount = $product->wishlistByUser()? $product->wishlistByUser()->count() : 0;

        if ($wishlistCount === 0) {

           return $product->wishlists()->create([
                'user_id' => Auth::user()->id,
            ]);
        }

        //delete
        $product->wishlistByUser()?->delete();

        return $wishlistCount;
	}

	/***** discount *****/


    public function checkValidityOf($coupon, $totalPrice, $givenDate)
    {
    	// if ($coupon->deduct->active === false) {
    	// 	return 'Inactive coupon!'
    	// }

    	if (!$this->isActive($coupon)) {
    		return $this->error;
    	}

    	if (!$this->isValidMinimumSpending($coupon->minimum_spending, $totalPrice)) {
    		return $this->error;
    	}

    	if (!$this->isValid('starting', $givenDate, $coupon->deduct->starting)) {
    		return $this->error;
    	}

    	if (!$this->isValid('ending', $givenDate, $coupon->deduct->ending)) {
    		return $this->error;
    	}

    	if (!$this->isValidUsageType($coupon)) {
    		return $this->error;
    	}
		
		return true;    	
    }

    public function isValidUsageType($coupon)
    {

    	if ($coupon->usage === Coupon::USAGE_SINGLE) {

    		$orders = Order::where('user_id', Auth::user()->id)->withCount(['coupon' => function($q) use ($coupon) {
    			$q->where('code', $coupon->code);
    		}])->get();


    		foreach ($orders as $order) {
    			if ($order->coupon_count >= 1) {
    				$this->error = 'This coupon already used!';
    				return false;
    			}
    		}    	
    	}    	

    	return true;
    }

    public function isActive($coupon)
    {
    	if ($coupon->deduct->active === true) {
    		return true;
    	}

    	$this->error = 'Inactive Coupon!';
    	
    	return false;
    }


    public function getDiscountAmount($coupon, $totalPrice)
    {
    	$discountAmount = 0;

    	if (is_null($coupon->categories)) {
    		
    		$discountAmount = $this->calculateDiscountOn($coupon, $totalPrice);

    	} else {

	    	$userCartItems = Cart::items();

	    	foreach ($userCartItems as $key => $item) {

	    		if (in_array($item->product->category_id, $coupon->categories)) {
	    			$price = $this->getPriceOfThe($item->product);

			    	$discountAmount = $discountAmount + $this->calculateDiscountOn($coupon, $price, $item->quantity);
	    		}
	    	}
    	}


 		// if ($discountAmount > 0 && !is_null($coupon->deduct->limit)) {
    		
	  //   	$limitValidity = $this->isDiscountAmountWithinLimit($discountAmount, $coupon->deduct->limit);

	  //   	if ($limitValidity === false ) {
	  //   		$discountAmount = $this->setLimitAsCouponDiscount($coupon->deduct->limit);
	  //   	}
   //  	}
    	$discountAmount = $this->amountLimit($discountAmount, $coupon);

    	return $discountAmount;
    }

    public function amountLimit($discountAmount, Coupon $coupon)
    {

 		if ($discountAmount > 0 && !is_null($coupon->deduct->limit)) {
    		
	    	$limitValidity = $this->isDiscountAmountWithinLimit($discountAmount, $coupon->deduct->limit);

	    	if ($limitValidity === false ) {
	    		$discountAmount = $this->setLimitAsCouponDiscount($coupon->deduct->limit);
	    	}
    	}

    	return $discountAmount;
    }

    public function calculateDiscountOn($coupon, $price, $quantity=1)
    {
    	if ($coupon->amount_type === Coupon::AMOUNT_TYPE_PERCENTAGE) {

	    	return ($price*$quantity)*($coupon->deduct->amount*0.01);
    	}
		
		return $coupon->deduct->amount;
    }

    public function getPriceOfThe($product)
    {
    	$productService = new ProductPriceService;
        $productService->initialize($product);

        return $productService->price() ?? round($product->price);
    }

    public function isValidMinimumSpending($minimum_spending, $totalPrice)
    {
    	if ( is_null($minimum_spending) ) {
    		return true;
    	}

    	if ($totalPrice >= $minimum_spending) {
    		return true;
    	}

        $this->error = "৳{$minimum_spending} minimum spending required";
    	
    	return false;
    }

    public function isDiscountAmountWithinLimit($discountAmount, $limit)
    {
    	return ($discountAmount <= $limit) ? true : false;  
    }

    public function setLimitAsCouponDiscount($limit)
    {
    	return $limit;
    }

    public function isValid(String $type, $givenDate, $date)
    {
    	if (is_null($date)) {
    		return false;
    	}

    	// $givenDate = date("Y-m-d");

    	if ($type == 'starting') {
    		$date = date("Y-m-d", strtotime($date));
            
            if ($givenDate >= $date) {
                return true;
            } 

            $this->error = "Offer/ Promotion is not strated yet!";
            return false;
    	}

    	$date = date("Y-m-d", strtotime($date));

        if ($givenDate <= $date) {
            return true;
        } 

        $this->error = 'Sorry! Offer/ Promotion is over.';
        
        return false;
    }

}