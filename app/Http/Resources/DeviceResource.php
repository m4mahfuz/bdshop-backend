<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DeviceResource extends JsonResource
{   
    public function toArray($request)
    {
        return [
          'id' => $this->id,
          'token' => $this->token,          
        ];
    }
}
