<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Offer extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts  = [
        'active' => 'boolean',
        'starting' => 'immutable_datetime',
        'ending' => 'immutable_datetime'
    ];

    const TYPE_PERCENTAGE = 1;
    const TYPE_FIXED = 2;
    const TYPE_BOGO = 3;
    const TYPE_BTGO = 4;

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot(
                'id',                
                // 'price', 
                'active'
            )->withTimestamps();
    }

    public function scopeActive($query) {
        $query->where('active', 1);
    }

    public function scopeValid($query) {
        $query->where('active', 1)
            ->where('starting', '<=', Carbon::now())
            ->where('ending', '>=', Carbon::now());
    }

    public function isValidOffer() {
        // dd($this->id, 
        //     $this->active == true &&
        //     $this->starting <= Carbon::now() &&
        //     $this->ending >= Carbon::now()
        // );
        $valid = ($this->active == true &&
            $this->starting <= Carbon::now() &&
            $this->ending >= Carbon::now()) ? true : false;
        return $valid;
    }

    public function convertToReadable($statusValue)
    {
        return collect([
         '1' => 'Percentage', 
         '2' => 'Fixed Amount',
         '3' => 'Buy 1 Get 1',
         '4' => 'Buy 2 Get 1',
        ])->first(function($value, $key) use ($statusValue) {
            return $key === $statusValue;
        });
    }

    public function offerPriceBasedOnProduct($price)
    {
        // $price = $this->pivot->price;

        if ($this->type === self::TYPE_PERCENTAGE) {
            return $price = round($price - ($price * $this->amount / 100));
        }

        if ($this->type === self::TYPE_FIXED) {
            return $price = $price - $this->amount;
        }

        // return $price;
        return null;
    }

    // public function setActive()
    // {
    //     $this->product->offers()->where('id', '<>', $this->id)->update(['active' => false]);
    //     $this->active = true;
    //     $this->save();
    // }

}
