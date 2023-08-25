<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{   
    public function toArray($request)
    {
        return [
          'id' => $this->id,
          'active' => $this->active,
          'image' => $this->image->name,
          'title' => $this->title,
          'description' => $this->description,
          'url' => $this->url
        ];
    }
}
