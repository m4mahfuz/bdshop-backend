<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingType extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'active' => 'boolean',
    ];

    const SHIPPING_TYPE_STANDARD = 1;
    const SHIPPING_TYPE_EXPRESS = 2;

    public function shipping()
    {
        return $this->belongsTo(Shipping::class);
    }

    public function shippingCharge()
    {
        return $this->hasOne(ShippingCharge::class);
    }

    public function scopeActive($query) {
        $query->where('active', 1);
    }

    public function deliveryTimeUnit()
    {
        return ($this->type === ShippingType::SHIPPING_TYPE_STANDARD) ? 'Days' : 'Hours';
    }

    public function convertToReadable($type)
    {
        return (self::SHIPPING_TYPE_STANDARD === $type) ? 'Standard' : 'Express'; 
    }
}
