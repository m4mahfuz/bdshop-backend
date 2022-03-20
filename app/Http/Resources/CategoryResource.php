<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            // 'productCount' => optional($this->products)->count(),
            'productCount' => $this->products ? $this->products->count() : 0,
        ];
    }
}
