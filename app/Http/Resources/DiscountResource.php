<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
{    
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            // 'rate' => $this->deduct?->rate,
            // 'amount_type' => $this->deduct?->amount_type,
            'amount' => $this->deduct?->amount,
            'active' => $this->deduct?->active,
            // 'minimum_spending' => $this->deduct?->minimum_spending,
            'limit' => $this->deduct?->limit,
            'starting' => $this->deduct?->starting,
            'ending' => $this->deduct?->ending,
            'products' => DiscountProductResource::collection($this->whenLoaded('products')),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
        ];

    }
}
