<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class ShipperResource extends JsonResource
{
    
    public function toArray($request)
    {
        // return parent::toArray($request);
        return Arr::except(parent::toArray($request), [

            'created_at', 'updated_at',          
        ]);

    }
}
