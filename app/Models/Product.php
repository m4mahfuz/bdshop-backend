<?php

namespace App\Models;

use App\Services\ProductPriceService;
use App\Services\ProductService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',        
        'active',
        'price',
        'unit',
        'unit_quantity',
        'discount_id',        
        // 'sku',
        'category_id',
    ];
    // private $price;
    // private $salePrice;
    // private $discountAmount;
    // private $discountLimit;
    // private $discountAmount;
    // // private $discountRate;
    // private $minimumSpending;
    // private $productStock;
    // protected $discountedCategory;

    protected $casts  = [
        'active' => 'boolean'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopeActive($query) {
        $query->where('active', 1);
    }


    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
          
    // public function categories()
    // {
    //     return $this->belongsToMany(Category::class);
    // }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getCategoryName()
    {
        if ($this->category->parent_id === null) {
            return   $this->category->name;
        }
        
        $parentCategory = Category::whereId($this->category->parent_id)->first();

        return "{$parentCategory->name}[{$this->category->name}]";
    }

    public function updateInventory(int $quantity)
    {
        $quantity = $this->inventory->quantity - $quantity;
        return $this->inventory()->update([
            'quantity' => $quantity
        ]);
    }

    public function offers()
    {
        return $this->belongsToMany(Offer::class)->withPivot(
                'id',                
                // 'price', 
                'active'
            )->withTimestamps();
    }

    public function activeOffer()
    {
        return $this->belongsToMany(Offer::class)
                    // ->withPivot(['price', 'active'])
                    ->withPivot(['active'])
                    ->wherePivot('active', true)
                    ->where(function ($query) {
                        $query->where('offers.active', true)
                              ->where('offers.starting', '<=', Carbon::now())
                              ->where('offers.ending', '>=', Carbon::now());
                    })
                    ->orderByPivot('created_at', 'desc')
                    ->first();
    }

    public function setLatestOfferAsActive()
    {
        // Get the latest offer for the product
        $latestOffer = $this->offers()->latest('created_at')->first();

        if ($latestOffer) {
            // Deactivate all other offers for the product
            // $this->offers()
            //      ->where('id', '<>', $latestOffer->id)
            //      ->updateExistingPivot(null, ['active' => false]);

                 //worked
            $this->offers()->wherePivot('offer_id', '<>', $latestOffer->id)->update(['offer_product.active'=>false]);


            // Activate the latest offer for the product
            $latestOffer->pivot->active = true;
            $latestOffer->pivot->save();
        }
    }

    public function deactivateOtherOfferForThisProduct(Offer $offer)
    {        

        // if ($latestOffer) {
            // Deactivate all other offers for the product
            
            $this->offers()->wherePivot('offer_id', '<>', $offer->id)->update(['offer_product.active'=>false]);


            // Activate the latest offer for the product
            // $offer->pivot->active = true;
            // $offer->pivot->save();
        // }
    }


    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function dailyDeal()
    {
        return $this->hasOne(DailyDeal::class);
                    
    }

    public function weeklyDeal()
    {
        return $this->hasOne(WeeklyDeal::class);
                    
    }

    public function discountedPrice()
    {
        $price = $this->isPriceAvailableFor('offer');

        if ($price === null || $price === 0) {
            $price = $this->isPriceAvailableFor('deal');

            if ($price === null) {
                $price = $this->calculate('price');
            }

        }
        return $price;
    }

    public function dealPrice()
    {
        // $dealType = session('dealType');
        
        $today = date("Y-m-d");

        // if ($dealType === 'daily') {

            // $dailyDealProduct = $this->whereHas('dailyDeal', function(Builder $query) {
            //     $query->where('active', true);
            // })->first();
        ////////////////////////////////////
            // $dailyDealProduct = $this->whereHas('dailyDeal', function(Builder $query) use ($today) {
            //     $query->where('active', true)
            //     ->whereHas('deal', function(Builder $qry) use ($today) {
            //     $qry->where('starting', '<=', $today)
            //         ->where('ending', '>=', $today);
            //     });
            // })->first();
            $dailyDealProduct = $this->getActiveDealType('dailyDeal');
            if ($dailyDealProduct) {
                return $this->dailyDeal?->deal->getPrice();
            }
        // }

        // if ($dealType === 'weekly') {
            // $weeklyDealProduct = $this->whereHas('weeklyDeal', function(Builder $query) {
            //     $query->where('active', true);
            // })->first();
            ////////////////////////////////////////////
            // $weeklyDealProduct = $this->whereHas('weeklyDeal', function(Builder $query) use ($today) {
            //     $query->where('active', true)
            //     ->whereHas('deal', function(Builder $qry) use ($today) {
            //     $qry->where('starting', '<=', $today)
            //         ->where('ending', '>=', $today);
            //     });
            // })->first();
            $weeklyDealProduct = $this->getActiveDealType('weeklyDeal');

            if ($weeklyDealProduct) {
                return $this->weeklyDeal?->deal->getPrice();
            }
        // }

        return null;
    }

    public function getActiveDealType(string $dealType)
    {
        // code...
        return $this->whereHas($dealType, function(Builder $query) {
                $query->where('active', true)
                ->whereHas('deal', function(Builder $qry) {
                $qry->where('starting', '<=', Carbon::now())
                    ->where('ending', '>=', Carbon::now());
                });
            })->first();
    }

    public function offerPrice()
    {       
        // $offer= $this->offers()
        //     ->wherePivot('active', 1)
        //     ->where(function ($query) {
        //         $query->where('offers.active', true)
        //           ->where('offers.starting', '<=', Carbon::now())
        //           ->where('offers.ending', '>=', Carbon::now());
        //         })
        //     ->orderByPivot('created_at', 'desc')
        //     ->first();
        $offer = $this->activeOffer();
        if ($offer) {
            // return $this->offers->getPrice();
            return $offer->offerPriceBasedOnProduct($this->price);
        }
        
        return null;
    }

    public function isPriceAvailableFor(string $type)
    {
        return $type === 'deal' ? $this->dealPrice() : $this->offerPrice();
    }

    
    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
    
    public function images()
    {
        return $this->morphMany(Image::class, 'owner');
    } 

    public function featuredImage()
    {
        return $this->belongsTo(Image::class, 'featured_image_id');
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function meta()
    {
        return $this->morphOne(Meta::class, 'owner');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class)->using(OrderProduct::class);            
    }    

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function wishlistByUser()
    {
        if (Auth::check()) {

            return $this->wishlists()->where('user_id', Auth::user()->id);//->first();
        }
        return;
    }

    public function totalPrice()
    {
        $price = $this->pivot->discounted_price ?? $this->pivot->price;

        return ($this->pivot->quantity * $price);
    }

    public function calculate(string $type)
    {
        $product = new ProductPriceService;
        
        $product->initialize($this);
        
        if ($type === 'amount') {
            return $product->getAmount();
        }        
        return $product->price();
        
    }
    
    // public function disputedProduct()
    // {
    //     return $this->pivot->dispute;
    
    // }

    /***************

    public function productPrice()
    {
        
        $this->initialize();
        // return [
        //   'price' =>  $this->price,
        //   'DL' => $this->discountLimit,
        //   'DR' => $this->discountRate,
        //   'salePrice' =>  $this->getSalePrice(),
        //   'isDiscountAvailable' =>  $this->isDiscountAvailable(),
        //   'isActiveDiscount' =>  $this->isActiveDiscount(),
        //   'isDiscountDateValid' => $this->isDiscountDateValid()
        // ];

        // return $this->isDiscountAvailable() ? $this->getSalePrice() : round($this->price);
        return $this->getSalePrice();


        // $salePrice = $this->getSalePrice();
        // $discountLimit = $this->discount->deduct->limit;

        // if ($this->isDiscountAvailable()) {
        //     if ($this->isDiscountLimitAvailable()) {
        //         if ($salePrice <= $discountLimit) {
        //             return $salePrice;
        //         } else {
        //             return $discountLimit;
        //         }
        //     }
        //     return $salePrice;
        // }

        // /return round($this->price);
    }

    public function isDiscountAvailable()
    {
        if (!$this->isActiveDiscount()) return false;
        if (!$this->isDiscountDateValid()) return false;
        if ($this->discount->deduct?->amount === null) return false;

        return true;           
    }

    public function isActiveDiscount() {
        return ($this->discount->deduct->active === true) ? true : false;
    }

    public function isDiscountDateValid() {

        $check = Carbon::today();
        $from = $this->discount->deduct->starting;
        $to = $this->discount->deduct->ending;
        
        return ($check >= $from && $check <= $to ) ? true : false;
    }
    
    // public function isMinimumSpendingAvailable() {
    //     return ($this->minimumSpending !== null) ? true : false;
    // }  
    
    // public function isDiscountLimitAvailable() {
    //     return ($this->discountLimit !== null) ? true : false;
    // }

    // public function isMinimumSpendingAvailable() {
    //     return ($this->discount->deduct?->minimum_spending !== null) ? true : false;
    // }  
    
    public function isDiscountLimitAvailable() {
        return ($this->discount->deduct->limit !== null) ? true : false;
    }
    
    public function getSalePrice() {
        // return round($this->price - $this->getDiscountAmount());
        $price = $this->price;
        $discountAmount = $this->getDiscountAmount();
        $discountLimit = $this->getDiscountLimit();

        if ($discountLimit !== null) {

            if ($discountAmount > $discountLimit) {
                return round($price - $discountLimit);
            }             
        }
        return round($price - $discountAmount);
    }    
   
    public function getAmount() {
        
        if ($this->isDiscountAvailable()) {

             return $this->discount->deduct->amount;
        }

        if ($this->category->isDiscountAvailable()) {

             return $this->category->discount->deduct->amount;
        } 

        return null;
    }
    
    public function getDiscountAmount() {
        
        return $this->discountAmount;
    }

    public function getDiscountLimit() {
        
        return $this->discountLimit;
    }
    
    // public function setDiscountLimit() {
    //     $this->discountLimit = $this->discount->deduct->limit;      
    // }    
    
    // public function setDiscountRate() {    
    //     $this->discountRate = $this->discount->deduct->rate;
    // }

    public function setDiscountAmount() {         
        
        // return $this->discountAmount = $this->price*($this->discount->deduct->amount *0.01);        
        if ($this->isDiscountAvailable()) {

            if ($this->isDiscountLimitAvailable()) {
                $this->discountLimit = $this->discount->deduct->limit;
            }

            return $this->discountAmount = $this->price*($this->discount->deduct->amount *0.01);        
        }
        if ($this->category) {

            if ($this->category->isDiscountAvailable($this->discountedCategory)) {
                // dump($this->category->isDiscountAvailable());
                // var_dump('mm');
                if ($this->category->isDiscountLimitAvailable($this->discountedCategory)) {
                    $this->discountLimit = $this->discountedCategory->discount->deduct->limit;
                }

                return $this->discountAmount = $this->price*($this->discountedCategory->discount->deduct->amount *0.01);        
            }
        }

        return 0;
    }

    // public function setMinimumSpending() {
    //     $this->minimumSpending = $this->discount->deduct?->minimum_spending;
    // }
    
    public function setProductStock() {
        $this->productStock = $this->inventory->quantity;
    }

    public function initialize() {
        // $this->setDiscountLimit();
        // $this->category?->load('discount.deduct');

        $this->discountedCategory = Category::find($this->category->id)?->load('discount.deduct');
        // dd($this->discountedCategory);
        $this->setDiscountAmount();
        // $this->setMinimumSpending();
        $this->setProductStock();
    }
    ****************/

}
