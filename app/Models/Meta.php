<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    protected $casts = [
        'keywords' => 'array'
    ];

    public function owner()
    {
        return $this->morphTo();
    }
}
