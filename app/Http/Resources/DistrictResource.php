<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class DistrictResource extends JsonResource
{    
    public function toArray($request)
    {        
        return Arr::except(parent::toArray($request), [
            'bn_name', 'url', 'lat', 'lon', 'created_at', 'updated_at',            
        ]);
    }
}
