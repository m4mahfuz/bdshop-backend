<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DealResource extends JsonResource
{   
    public function toArray($request)
    {
        return [
          'id' => $this->id,
          // 'amount_type' => $this->amount_type,
          // 'amount' => $this->amount,
          'product' => DealProductResource::make($this->whenLoaded('product')),

        ];
    }
}
