<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderLog extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'date_time' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // public function updateStatusTo($status)
    // {
    //     return $this->update([
    //         'status' => $status,
    //     ]);
    // }
}
