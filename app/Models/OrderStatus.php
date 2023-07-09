<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'active' => 'boolean',
    ];

    const RECEIVED = 1;
    const PAID = 2;
    const PENDING = 3;
    const PROCESSING = 4;
    const SHIPPED = 5;
    const CANCELLED = 6;
    const DELIVERED = 7;

    // public function orders()
    // {
    //     return $this->hasMany(Order::class);
    // }

    public function scopeActive($query) {
        $query->where('active', 1);
    }

    public function convertToReadable($statusValue)
    {
        return collect([
         '1' => 'Received', 
         '2' => 'Paid',
         '3' => 'Pending',
         '4' => 'Processing',
         '5' => 'Shipped',
         '6' => 'Cancelled',
         '7' => 'Delivered',
        ])->first(function($value, $key) use ($statusValue) {
            return $key === $statusValue;
        });
    }


}
