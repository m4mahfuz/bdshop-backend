<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class OrderAdditionalResource extends JsonResource
{
    
    public function toArray($request)
    {
        // return [
        //     'id' => $this->id,
        //     'title' => $this->title
        // ];

        return Arr::except(parent::toArray($request), [
            'id','order_id', 'created_at', 'updated_at',
        ]);        
    }
}
