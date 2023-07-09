<?php

namespace App\Http\Resources;

use App\Http\Resources\OfferProductResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class OfferResource extends JsonResource
{   
    public function toArray($request)
    {
        return [
          'id' => $this->id,
          'name' => $this->name,
          'type' => $this->convertToReadable($this->type),
          'amount' => round($this->amount),
          'starting' => $this->starting->format('F j, Y'),
          'ending' => $this->ending->format('F j, Y'),
          'active' => $this->active,
          'products' => OfferProductResource::collection($this->whenLoaded('products')),

            // $this->merge(Arr::except(parent::toArray($request), [
            //     'created_at', 'updated_at', 
            // ]))
        ];
        // return parent::toArray($request);
    }
}
