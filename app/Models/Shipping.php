<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'active' => 'boolean',
    ];

    // public function shipments()
    // {
    //     return $this->hasMany(Shipment::class);
    // }
    public function shippingTypes()
    {        
        return $this->hasMany(ShippingType::class);
    }

    public function orders()
    {        
        return $this->hasMany(Order::class);
    }

    public function scopeActive($query) {
        $query->where('active', 1);
    }

    public static function chargeFor($city, $type=1)
    {        
        // return Shipping::whereCity($city)->active()->first()->shippingTypes()->active()->whereType($type)->first()->shippingCharge()->active()->first()?->charge;
        return self::byCity($city)->shippingTypes()->active()->whereType($type)->first()->shippingCharge()->active()->first()?->charge;
    }

    public static function byCity($city)
    {
        return self::whereCity($city)->active()->first();
    }
 
}
