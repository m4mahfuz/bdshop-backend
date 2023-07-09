<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class ShipmentResource extends JsonResource
{
    
    public function toArray($request)
    {
        // return parent::toArray($request);

        return [
            // 'shipping_address' => ShippingAddressResource::make($this->whenLoaded('shippingAddress')),
            'shipper' =>ShipperResource::make($this->whenLoaded('shipper')),

            $this->merge(Arr::except(parent::toArray($request), ['order_id', 'shipping_address_id', 'created_at', 'updated_at'          
            ]))
        ];
    }
}
