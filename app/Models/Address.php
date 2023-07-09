<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts  = [
        'active' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query) {
        $query->where('active', 1);
    }

    public static function city(Address $address) {
        return $address->city;
    }

}
