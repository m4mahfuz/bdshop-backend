<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class ImageResource extends JsonResource
{    
    public function toArray($request)
    {
        // return [
        //    // 'path' => Storage::url($this->path),
        //    // 'path' => $this->path,

        //     $this->merge(parent::toArray($request))
        // ];
        // return parent::toArray($request);
        // return [
        //     'featured_image' => $this->owner()->featuredImage()
        // ];
        return Arr::except(parent::toArray($request), [
            'owner_id', 'owner_type', 'created_at', 'updated_at',            
        ]);
    }
}
