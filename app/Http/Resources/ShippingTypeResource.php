<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShippingTypeResource extends JsonResource
{
    
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'active' => $this->active,
            'name' => $this->convertToReadable($this->type),
            'type' => $this->type,
            'delivery_time_min' => $this->delivery_time_min,
            'delivery_time_max' => $this->delivery_time_max,
            'delivery_time_unit' => $this->deliveryTimeUnit(), 
            'shipping_charge' => ShippingChargeResource::make($this->whenLoaded('shippingCharge'))
            // 'shipping_charge' => $this->shippingCharge->charge,
            // 'shipping_charge_active' => $this->shippingCharge->active,
            
        ];
    }
}