<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Services\ProductPriceService;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountProductResource extends JsonResource
{   
    public function toArray($request)
    {
        
        return [
          'id' => $this->id,
          'name' => $this->name,
          'slug' => $this->slug,          
          'original_price' => round($this->price),                    
          'price' => $this->discountedPrice(),
          'unit' => $this->unit,
          'unit_quantity' => $this->unit_quantity,
          'stock_quantity' => $this->inventory->quantity,
          'sku' => $this->inventory->sku,
          // 'category' => CategoryResource::make($this->category),
          'category' => $this->getCategoryName(),
          'featured_image' => ImageResource::make($this->featuredImage),        
          'active' => $this->active? true: false,
        ];
    }
}
