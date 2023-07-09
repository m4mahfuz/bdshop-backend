<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WeeklyDealCollection extends ResourceCollection
{    
    public function toArray($request)
    {
        return ['data' => $this->collection];
    }
}
