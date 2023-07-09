<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DailyDealCollection extends ResourceCollection
{    
    public function toArray($request)
    {
        // return parent::toArray($request);
         return ['data' => $this->collection];
    }
}
