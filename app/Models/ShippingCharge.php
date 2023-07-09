<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCharge extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'active' => 'boolean',
    ];

    public function shippingType()
    {
        return $this->belongsTo(ShippingType::class);
    }

    public function scopeActive($query) {
        $query->where('active', 1);
    }
}
