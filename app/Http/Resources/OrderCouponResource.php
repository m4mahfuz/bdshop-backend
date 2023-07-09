<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderCouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        // Arr::except(parent::toArray($request), [
        //     'user_id', 'created_at', 'updated_at',
        // ]);
        return [
            'code' => $this->code,
            'discounted_amount' => $this->amount
        ];
    }
}
