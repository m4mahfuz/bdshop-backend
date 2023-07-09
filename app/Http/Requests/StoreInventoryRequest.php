<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreInventoryRequest extends FormRequest
{   
   
    // public function getPrice(): float
    // {
    //     return $this->price;
    // }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
    
    // public function getProductId(): int
    // {
    //     return $this->product_id;
    // }

    public function getSku(): ?string
    {
        if (is_null($this->sku)) {
            return Str::random(12);
        }
        return $this->sku;
    }

    public function rules()
    {
        return [            
            'sku' => 'nullable|sometimes|string|max:12',
            'quantity' => 'required|numeric',
            // 'product_id' =>'required|exists:products,id',
            // 'discount_id' => 'nullable|sometimes|exists:discounts,id'
        ];
    }
}
