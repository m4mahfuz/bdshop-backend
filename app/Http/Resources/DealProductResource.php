<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Services\ProductPriceService;
use Illuminate\Http\Resources\Json\JsonResource;

class DealProductResource extends JsonResource
{   
    public function toArray($request)
    {
        

        return [
          'id' => $this->id,
          'name' => $this->name,
          'slug' => $this->slug,          
          'original_price' => round($this->price),                    
          'price' => round($this->dealPrice()),
          'unit' => $this->unit,
          'unit_quantity' => $this->unit_quantity,
          'quantity' => $this->inventory->quantity,
          'sku' => $this->inventory->sku,
          // 'wishlistCount' => $this->wishlistByUser()? $this->wishlistByUser()->count() : 0,
          'category' => CategoryResource::make($this->whenLoaded('category')),
          // 'discount' => DiscountResource::make($this->whenLoaded('discount')),    
          // 'images' => ImageResource::collection($this->whenLoaded('images')),
          'tags' => TagResource::collection($this->whenLoaded('tags')),
          'featured_image' => ImageResource::make($this->whenLoaded('featuredImage')),        
          // 'amount_type' => $this->dailyDeal->deal->getAmountType(),
          // 'amount' => $this->dailyDeal->deal->amount,
          // 'starting' => \Carbon\Carbon::parse($this->dailyDeal->deal->starting)->format('Y-m-d h:i A'),
          // 'ending' => \Carbon\Carbon::parse($this->dailyDeal->deal->ending)->format('Y-m-d h:i A'),
          'deal' => true
        ];
    }
}
