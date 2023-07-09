<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deduct extends Model
{
    use HasFactory;
    protected $guarded = [];    
    protected $casts = [
        'active' => 'boolean',
        // 'starting' => 'date',
        // 'ending' => 'date'
    ];


    public function deductable()
    {
        return $this->morphTo();
    }
}
