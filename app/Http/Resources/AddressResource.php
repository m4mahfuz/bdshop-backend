<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray($request)
    {
        // return parent::toArray($request);      
        return [
            'id' => $this->id,
            'title' => $this->title, 
            'name' => $this->name ?? $this->user->name,
            'phone' => $this->phone ?? $this->user->phone,
            'active' => $this->active,
            // 'email' => $this->user->email,
            'address_line' => $this->address_line,
            'city' => $this->city,
            'postal_code' => $this->postal_code ?? null,
            // 'shipping_charge' => Shipping::chargeFor($this->city)            
        ];
    }
}
