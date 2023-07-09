<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class OrderResource extends JsonResource
{
    
    public function toArray($request)
    {

        return [
            'shipping_address' => ShippingAddressResource::make($this->whenLoaded('shippingAddress')),
            'coupon' => OrderCouponResource::make($this->whenLoaded('coupon')),
            'customer' => UserResource::make($this->whenLoaded('user')),
            'shipment' => ShipmentResource::make($this->whenLoaded('shipment')),
            // 'shipment' => ShipmentResource::make($this->shippingAddress->shipment),
            // 'shipper' => ShipperResource::make($this->shippingAddress?->shipment?->shipper),  
            'products' => OrderProductResource::collection($this->whenLoaded('products')),
            'placed_on' => $this->created_at->format('jS M Y, h:i a'),  
            'delivery_earliest' => $this->deliveryEarliest() ,            
            'delivery_latest' => $this->deliveryLatest(),
            'payment_method' => $this->paymentMethod(),            
            'shipping_type' => $this->shippingType(),            
            'today' => \Carbon\Carbon::now()->format('F j, Y'),
            'delivered_on' => $this->deliveredOn(),
            
            $this->merge(Arr::except(parent::toArray($request), [
                'user_id','shipping_id', 'shipping', 'created_at', 'updated_at',          
            ]))
        ];
    }
}
