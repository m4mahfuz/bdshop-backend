<?php

namespace App\Models;

use App\Models\Order;
use App\Services\ProductService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProductDispute extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function orderProduct()
    {
        return $this->belongsTo(OrderProduct::class);
    }

   // public static function amountOf($items)
   // {
   //     $amount = 0;

   //     foreach ($items as $item) {
   //       $amount = $amount + self::priceOf($item['id']);
   //     }
   //     return $amount;
   // }

   public static function amountOf(Order $order, ProductService $product, $items)
   {
        $amount = $order->total;
        $code = $order->coupon->code;
        $couponInfo = Coupon::infoBy($code);

        $givenDate = $order->created_at;

        $amount = 0;
        $couponDiscountAmount = 0;

       foreach ($items as $item) {
         $amount = $amount + self::priceOf($item['id']);

         $couponDiscountAmount = $couponDiscountAmount + self::couponDiscountAmount($item['id'], $product, $couponInfo, $amount, $givenDate);
       }

       $couponDiscountAmount = $product->amountLimit($couponDiscountAmount, $couponInfo);

       return [
            'amount' => $amount,
            'couponDiscountAmount' => $couponDiscountAmount
        ];
   }


   public static function couponDiscountAmount($orderProductId, ProductService $product, Coupon $coupon, $orderTotalPrice, $givenDate)
   {

        $orderProduct = OrderProduct::find($orderProductId);  
        $price = $orderProduct->discounted_price ?? $orderProduct->price;
        $quantity = $orderProduct->quantity;  

        $response = $product->checkValidityOf($coupon, $orderTotalPrice, $givenDate);
        
        $discountAmount = 0;  

        if ($response === true) {
            if (is_null($coupon->categories)) {
            
                $discountAmount = $product->calculateDiscountOn($coupon, $price, $quantity);

            } else {
                if (in_array($orderProduct->product->category_id, $coupon->categories)) {
                    // $price = $this->getPriceOfThe($item->product);

                    $discountAmount = $product->calculateDiscountOn($coupon, $price, $quantity);
                }

            }  
        }
        
        return $discountAmount;
   }
   
   public static function priceOf($item)
   {
        $orderProduct = OrderProduct::find($item);    
        $price = $orderProduct?->discounted_price ?? $orderProduct?->price;
        return ($price * $orderProduct->quantity);
   }

    public static function entry($items, $status='')
    {
        foreach ($items as $item) {
            self::create([
                'order_product_id' => $item['id'],
                'status' => $status,
                'reason' => $item['reason'],
            ]);
        }  
        return;         
    }
}
