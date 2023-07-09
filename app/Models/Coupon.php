<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'categories' => 'array',
        'users' => 'array'
    ];

    const USAGE_SINGLE = 1;
    const USAGE_MULTIPLE = 2;
    const AMOUNT_TYPE_PERCENTAGE = 1;
    const AMOUNT_TYPE_FIXED = 2;

    public function deduct()
    {
        return $this->morphOne(Deduct::class, 'deductable');
    }    

    // public function orders()
    // {
    //     return $this->hasMany(Order::class);
    // }

    // public function getCoupoInfoBy(String $code)
    // {
    //     return $this->where('code', $code)->first();
    // }

    public static function infoBy($code)
    {
        return self::whereCode($code)->first();
    }

    public function isMinimumSpendingAvailable() {
        return ($this->minimum_spending !== null) ? true : false;
    }  

}
