<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts  = [
        'active' => 'boolean'
    ];

    public static function tokens()
    {
      return self::active()->get('token');  
    }

    public function scopeActive($query) {
        $query->where('active', 1);
    }

}
