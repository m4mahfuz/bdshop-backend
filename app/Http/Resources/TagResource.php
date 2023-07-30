<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'active' => $this->active,
            'category' => CategoryResource::make($this->whenLoaded('category')),
            // 'description' => $this->description,
            // 'parent_id' => $this->parent_id,
            // 'children' => $this->whenLoaded('children'),
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'productCount' => $this->products->count(),
        ];
    }
}