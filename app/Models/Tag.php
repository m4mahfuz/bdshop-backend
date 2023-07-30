<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    protected $casts  = [
        'active' => 'boolean'
    ];


    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public static function getTagsByCategory(int $id)
    {
        return self::whereCategoryId($id)
            ->whereActive(true)
            ->get();
    }
}
