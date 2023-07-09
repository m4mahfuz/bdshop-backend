<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function product()
    {
        return $this->hasOne(Product::class);
    }

    // public function discount()
    // {
    //     return $this->belongsTo(Discount::class);
    // }

}
