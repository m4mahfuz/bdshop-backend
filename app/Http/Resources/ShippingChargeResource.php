<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShippingChargeResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'active' => $this->active,
            'charge' => $this->charge,
        ];
    }
}