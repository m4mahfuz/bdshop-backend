<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class ShippingAddressResource extends JsonResource
{
    public function toArray($request)
    {
        return (Arr::except(parent::toArray($request), [
                'user_id', 'created_at', 'updated_at',          
            ])); 
        // return parent::toArray($request);
    }
}
