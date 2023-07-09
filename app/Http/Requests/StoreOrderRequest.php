<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{        
    
    public function getAddressId(): int
    {
        return $this->address_id;
    }    

    public function getPaymentMethod(): int
    {
        return $this->payment_method;
    }

    public function getCouponCode (): ?string
    {
        // if (session()->has('couponCode')) {
        //     return (session('couponCode') === $this->coupon_code) ? session('couponCode') : null;
        // }
        // return null;
        if ($this->coupon_code !== null && $this->coupon_code === session('couponCode')) {
             return session('couponCode');
        }
        return null;
    }

    // public function getCouponDiscountedAmount(): ?int
    // {
    //     // if (session()->has('couponCode') && session()->has('couponDiscountdAmount')) {
    //     //     return (session('couponCode') === $this->coupon_code) ? session('couponDiscountdAmount') : null;
    //     // }
    //     // return null;

    //     if ($this->coupon_code !== null && $this->coupon_code === session('couponCode')) {
    //          return session('couponDiscountdAmount');
    //     }

    //     return null;
    // }

    public function getShippingType(): int
    {
        return $this->shipping_type;
    }

    public function getTotalPrice(): int
    {
        return $this->total_price;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }
    
    public function getAction(): string
    {
        return $this->action;
    }
    
    public function rules()
    {
        return [            
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|integer',
            'shipping_type' => 'required|integer|exists:shipping_types,type',
            'total_price' => 'required',
            'coupon_code' => 'nullable|sometimes|string|exists:coupons,code'
        ];
    }
}
