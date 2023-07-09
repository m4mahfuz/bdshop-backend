<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use HasFactory;
     protected $guarded = [];
    
    protected $casts = [
        'active' => 'boolean',
    ];

    public function image()
    {
        return $this->morphOne(Image::class, 'owner');
    } 

    public function add($data)
    {
        return $this->create($data);
    }

    public function saveImageOf($image, $slide)
    {
        $slide->image()->create([
            'name' => $image
        ]);
        return;
    }     

}
