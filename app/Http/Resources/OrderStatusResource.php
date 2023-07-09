<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class OrderStatusResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title
        ];

        // return Arr::except(parent::toArray($request), [
        //     'id','active', 'created_at', 'updated_at',          
        // ]);        
    }
}
