<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DailyDealResource extends JsonResource
{   
    public function toArray($request)
    {
        return [
          'id' => $this->id,
          'active' => $this->active,
          // 'original_price' => round($this->price),
          'original_price' => round($this->product->price),
          // 'price' => round($this->product->dealPrice()),
          'price' => is_null(auth('admin')->user()) ? round($this->product->dealPrice()) : round($this->deal->getPrice()),
          'amount_type' => $this->deal->getAmountType(),
          'amount' => $this->deal->amount,
          'starting' => \Carbon\Carbon::parse($this->deal->starting)->format('Y-m-d, h:i A'),
          'ending' => \Carbon\Carbon::parse($this->deal->ending)->format('Y-m-d, h:i A'),
          'product' => DealProductResource::make($this->whenLoaded('product')),
        ];
    }
}
