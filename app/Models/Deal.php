<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;
    protected $guarded = [];    
    protected $casts = [
        'active' => 'boolean',
        'starting' => 'datetime',
        'ending' => 'datetime'
    ];

    const AMOUNT_TYPE_PERCENTAGE = 1;
    const AMOUNT_TYPE_FIXED = 2;

    public function dealable()
    {
        return $this->morphTo();
    }

    public function getAmountType()
    {
        if ($this->amount_type === self::AMOUNT_TYPE_PERCENTAGE) {
                   return 'Percentage';
        }        
        return 'Fixed';
    }

    public function getPrice()
    {
        $productPrice = $this->dealable->product->price;
        // $productPrice = $this->dealable->price;

        if ($this->amount_type === self::AMOUNT_TYPE_PERCENTAGE) {
                   return $price = round($productPrice - ($productPrice * ($this->amount * 0.01) ));     
        }

        return ($productPrice - $this->amount);
    }

}
