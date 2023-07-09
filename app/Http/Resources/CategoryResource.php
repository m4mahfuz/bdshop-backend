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
            'slug' => $this->slug,
            'description' => $this->description,
            'parent_id' => $this->parent_id,
            'active' => $this->active,
            'icon' => $this->icon,
            'image' => $this->image?->name,
            'children' => $this->whenLoaded('children'),
            'meta' => $this->whenLoaded('meta'),
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'productCount' => $this->products->count(),
        ];
    }
}
