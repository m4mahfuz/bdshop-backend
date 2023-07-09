<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class CouponResource extends JsonResource
{    
    public function toArray($request)
    {
        return parent::toArray($request);
        // return Arr::except(parent::toArray($request), [
        //     'id','order_id', 'created_at', 'updated_at',          
        // ]);        

    }
}
