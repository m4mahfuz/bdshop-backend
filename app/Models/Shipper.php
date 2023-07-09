<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipper extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    public function scopeActive($query) {
        $query->where('active', 1);
    }


}
