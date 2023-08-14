<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class ShippingResource extends JsonResource
{    
    public function toArray($request)
    {        
        return [
            'shipping_types' => ShippingTypeResource::collection($this->whenLoaded('shippingTypes')),
            
            $this->merge(Arr::except(parent::toArray($request), [
                'created_at', 'updated_at',          
            ]))
        ]; 
    }
}