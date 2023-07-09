<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyDeal extends Model
{
    use HasFactory;
    use HasFactory;
    protected $guarded = [];
    protected $casts  = [
        'active' => 'boolean'
    ];

    public function deal()
    {
        return $this->morphOne(Deal::class, 'dealable');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($deal) {
            $deal->deal()->delete();
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
