<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductImageResource extends JsonResource
{   
    public function toArray($request)
    {

        return [
          // 'id' => $this->id,
          // 'name' => $this->name,
          // 'slug' => $this->slug,
          'images' => ImageResource::collection($this->whenLoaded('images')),
          'featured_image' => ImageResource::make($this->whenLoaded('featuredImage')),    
        ];
    }
}
