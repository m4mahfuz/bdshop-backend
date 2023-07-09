<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductResource extends JsonResource
{   
    public function toArray($request)
    {
        

        return [
          'id' => $this->id,
          'name' => $this->name,
          'slug' => $this->slug,          
          // 'original_price' => round($this->pivot->price),                    
          'original_price' => round($this->price),                    
          // 'price' => $this->discountedPrice(), //round($this->discountedPrice()),
          'unit' => $this->unit,
          'unit_quantity' => $this->unit_quantity,
          'stock_quantity' => $this->inventory->quantity,
          'sku' => $this->inventory->sku,
          // 'category' => CategoryResource::make($this->category),
          // 'tags' => TagResource::collection($this->tags),
          'featured_image' => ImageResource::make($this->featuredImage),        
          'pivot' => $this->pivot,    
        ];
    }
}
