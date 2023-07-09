<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Services\ProductPriceService;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{   
    public function toArray($request)
    {
        // $product = new ProductPriceService;
        // $product->initialize($this);

        return [
          'id' => $this->id,
          'name' => $this->name,
          'slug' => $this->slug,
          'description' => $this->description,
          'active' => $this->active,
          'original_price' => round($this->price),
          // 'price' => $this->price,
          /*'amount' => $product->getAmount(),
          'price' => $product->price(),*/
          'amount' => $this->calculate('amount'),
          'price' => $this->calculate('price'),
          // 'price' => $this->productPrice(),
          'unit' => $this->unit,
          'unit_quantity' => $this->unit_quantity,
          'quantity' => $this->inventory->quantity,
          'sku' => $this->inventory->sku,
          'wishlistCount' => $this->wishlistByUser()? $this->wishlistByUser()->count() : 0,
          // 'categories' => CategoryResource::collection($this->whenLoaded('categories')),
          // 'inventory' => InventoryResource::make($this->whenLoaded('inventory')),
          'category' => CategoryResource::make($this->whenLoaded('category')),
          'discount' => DiscountResource::make($this->whenLoaded('discount')),    
          'images' => ImageResource::collection($this->whenLoaded('images')),
          'tags' => TagResource::collection($this->whenLoaded('tags')),
          'featured_image' => ImageResource::make($this->whenLoaded('featuredImage')),    
          'meta' => $this->whenLoaded('meta'),    
          // 'deal' => false,
        ];
    }
}
