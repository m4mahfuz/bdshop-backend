<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderLogResource extends JsonResource
{
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at->format('jS M Y, h:i a')
        ];
    }
}
