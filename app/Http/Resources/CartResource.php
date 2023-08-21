<?php

namespace App\Http\Resources;

// use App\Services\ProductPriceService;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{   
    public function toArray($request)
    {
        // $product = new ProductPriceService;
        // $product->initialize($this->product);

        return [
          'id' => $this->product_id,
          'name' => $this->product->name, 
          'unit' => $this->product->unit,
          'unit_quantity' =>$this->product->unit_quantity,
          'image' => $this->product->featuredImage?->name,
          'original_price' => round($this->product->price),
          // 'price' => $this->product->dealPrice()?? $this->product->calculate('price'),//$product->price(),  
          // 'price' => $this->productDiscountedPrice(),
          'price' => $this->product->discountedPrice(),
          'amount' => $this->product->calculate('amount'),
          'stock_quantity' => $this->product->inventory->quantity,
          'quantity' => $this->quantity,          
        ];
    }
}
