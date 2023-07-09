<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InventoryResource extends JsonResource
{    
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'quantity' => $this->quantity,
            'product' => $this->whenLoaded('product')
            // 'productName' => $this->whenLoaded('product', fn() => $this->product->name),
            // 'productName' => $this->product->name,
            // 'productDescription' => $this->product->description,
            // 'discount' => DiscountResource::make($this->whenLoaded('discount')),            
        ];
    }
}
