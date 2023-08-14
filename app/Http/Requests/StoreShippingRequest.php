<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreShippingRequest extends FormRequest
{      
    public function getShippingActive(): bool
    {
        return $this->active;
    }
    
    public function getShippingCity(): string
    {
        return $this->city;
    }

    public function getShippingTypesActive(): bool
    {
        return $this->input('shipping_types.active');
    }

    public function getShippingType(): int
    {
        return $this->input('shipping_types.type');
    }

    public function getShippingDeliveryTimeMinimum(): int
    {
        return $this->input('shipping_types.delivery_time_min');
    }

    public function getShippingDeliveryTimeMaximum(): int
    {
        return $this->input('shipping_types.delivery_time_max');
    }

    public function getShippingChargeActive(): bool
    {
        return $this->input('shipping_types.shipping_charge.active');
    }

    public function getShippingCharge(): int
    {
        return $this->input('shipping_types.shipping_charge.charge');
    }
    
    
    public function rules()
    {
        return [            
            'city' => [
                'sometimes',
                'required',
                'string',
                // Rule::unique('shippings', 'city')->ignore($this->shipping?->id)
            ],
            'active' => 'sometimes|required|boolean',
            'shipping_types.active' => 'sometimes|required|boolean',        
            'shipping_types.type' => 'sometimes|required|integer',        
            'shipping_types.delivery_time_min' => 'sometimes|required|integer',        
            'shipping_types.delivery_time_max' => 'sometimes|required|integer',        
            'shipping_types.shipping_charge.active' => 'sometimes|required|boolean',        
            'shipping_types.shipping_charge.charge' => 'sometimes|required|integer',        
        ];
    }    
}
