<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;


class OrderProduct extends Pivot
{
    use HasFactory;
    
    public $incrementing = true;

    public function disputed()
    {
        return $this->hasOne(OrderProductDispute::class, 'order_product_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
