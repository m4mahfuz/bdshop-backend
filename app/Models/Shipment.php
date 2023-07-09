<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'active' => 'boolean',
    ];

    // public function charge()
    // {
    //     return $this->belongsTo(Shipping::class);
    // }
    public function shipper()
    {
        return $this->belongsTo(Shipper::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(ShippingAddress::class);
    }
}
