<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = [
        // 'session_id',
        'product_id',
        'user_id',
        'quantity',        
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // public function productDiscountedPrice()
    // {
    //     $price = $this->product->isPriceAvailableFor('offer');

    //     if ($price === null || $price === 0) {
    //         // $weeklyDealProduct = $this->product->getActiveDealType('weeklyDeal');

    //         // if ($weeklyDealProduct) {
    //         //     // $dealOriginalPrice = $this->product->weeklyDeal->price;
    //         //     $price = $this->product->weeklyDeal?->deal->getPrice();
    //         // }

    //         // $dailyDealProduct = $this->product->getActiveDealType('dailyDeal');

    //         // if ($dailyDealProduct) {
    //         //     // $dealOriginalPrice = $this->product->dailyDeal->price;
    //         //     $price= $this->product->dailyDeal?->deal->getPrice();
    //         // }
    //         $price = $this->product->isPriceAvailableFor('deal');

    //         if ($price === null) {
    //             $price = $this->product->calculate('price');
    //         }

    //     }
    //     return $price;
    // }

    // public static function items()
    // {
    //     if (Auth::check()) {
    //         $items = Cart::with('product')->where([
    //             'product_id' => $request->getProductId(),
    //             'user_id' => Auth::user()->id
    //         ])->get();
    //     } else {
    //         $items = Cart::with('product')->where([
    //             'product_id' => $request->getProductId(),
    //             'session_id' => Session::get('session_id')
    //         ])->get();
    //     }
        
    //     return $items;        
    // }

    public static function items()
    {
        $items = null;
        
        if (Auth::check()) {
            $items = Cart::with('product')->where([
                // 'product_id' => $request->getProductId(),
                'user_id' => Auth::user()->id
            ])->get();
        }
        
        return $items;        
    }   

    public static function empty()
     {
        Cart::where('user_id', Auth::user()->id)->delete();
        return;

     } 
}
