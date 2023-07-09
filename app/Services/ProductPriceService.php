<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Carbon;


class ProductPriceService 
{
	private $discountLimit;
    private $discountAmount;
    private $minimumSpending;
    private $productStock;
    protected $product;

    public function initialize($product) {
        
        $this->product = $product->load('category.discount.deduct');

        // if (is_null($this->product->discount)) {
        //     $this->discountAmount = null;
        //     return;
        // }        
        
        $this->setDiscountAmount();
        // $this->setMinimumSpending();
        $this->setProductStock();
    }
	
	public function price()
    {
        return $this->getSalePrice();
    }

    public function getSalePrice() {

        $discountAmount = $this->getDiscountAmount();
        
        if (is_null($discountAmount)) {
            return $discountAmount;
        }

        $price = $this->product->price;
        $discountLimit = $this->getDiscountLimit();

        if ($discountLimit !== null) {

            if ($discountAmount > $discountLimit) {
                return round($price - $discountLimit);
            }             
        }
        return round($price - $discountAmount);
    }    

    public function isDiscountAvailableFor($type='product')
    {
        if(!$this->hasDiscountFor($type)) return false;
        if (!$this->isActiveDiscountFor($type)) return false;
        if (!$this->isDiscountDateValidFor($type)) return false;
        if (!$this->hasAmountFor($type)) return false;

        return true;           
    }

    public function hasDiscountFor($type='product') {

        if ($type === 'product') {
        
            if (is_null($this->product->discount)) {
                $this->discountAmount = null;
                return false;
            }
            return true;
        }

        if (is_null($this->product->category?->discount)) {
            $this->discountAmount = null;
            return false;
        }
        return true;
    }

    public function isActiveDiscountFor($type='product') {
    	if ($type === 'product') {

	        return ($this->product->discount->deduct->active === true) ? true : false;
    	}
        return ($this->product->category->discount->deduct->active === true) ? true : false;
    }

    public function isDiscountDateValidFor($type='product') {

        // $today = Carbon::today();        
        $today= date("Y-m-d");
        // $today = date("Y-m-d", strtotime($today));

        if ($type === 'product') {

        	$from = date("Y-m-d", strtotime($this->product->discount->deduct->starting));
	        $to = date("Y-m-d", strtotime($this->product->discount->deduct->ending));
        	
        } 
        else {        	
	        $from = date("Y-m-d", strtotime($this->product->category->discount->deduct->starting));
	        $to = date("Y-m-d", strtotime($this->product->category->discount->deduct->ending));
        }

    	return (($today >= $from) && ($today <= $to) ) ? true : false;
        
    }
    
    // public function isMinimumSpendingAvailable() {
    //     return ($this->minimumSpending !== null) ? true : false;
    // }  
    
    // public function isDiscountLimitAvailableFor() {
    //     return ($this->discountLimit !== null) ? true : false;
    // }

    // public function isMinimumSpendingAvailable() {
    //     return ($this->discount->deduct?->minimum_spending !== null) ? true : false;
    // }  

    public function hasAmountFor($type='product')
    {
         if ($type === 'product') {         
            return (is_null($this->product->discount->deduct->amount)) ?  false : true;
        } 

        return (is_null($this->product->category->discount->deduct->amount)) ? false : true;             
    }
    
    public function isDiscountLimitAvailableFor($type='product') {
    	if ($type === 'product') {

	        return ($this->product->discount->deduct->limit !== null) ? true : false;
    	}
        return ($this->product->category->discount->deduct->limit !== null) ? true : false;
    }
           
    public function getAmount() {
        
        if ($this->isDiscountAvailableFor('product')) {

             return $this->product->discount->deduct->amount;
        }

        if ($this->isDiscountAvailableFor('category')) {

             return $this->product->category->discount->deduct->amount;
        } 

        return null;
    }
    
    public function getDiscountAmount() {
        
        return $this->discountAmount;
    }

    public function getDiscountLimit() {
        
        return $this->discountLimit;
    }
    
    public function setDiscountAmount() {         
        
        if ($this->isDiscountAvailableFor('product')) {

            if ($this->isDiscountLimitAvailableFor('product')) {
                $this->discountLimit = $this->product->discount->deduct->limit;
            }

            return $this->discountAmount = $this->product->price*($this->product->discount->deduct->amount *0.01);        
        }

        if ($this->isDiscountAvailableFor('category')) {
            if ($this->isDiscountLimitAvailableFor('category')) {
                $this->discountLimit = $this->product->category->discount->deduct->limit;
            }

            return $this->discountAmount = $this->product->price*($this->product->category->discount->deduct->amount *0.01);        
        }

        return null;
    }

    // public function setMinimumSpending() {
    //     $this->minimumSpending = $this->discount->deduct?->minimum_spending;
    // }
    
    public function setProductStock() {
        $this->productStock = $this->product->inventory->quantity;
    } 

}