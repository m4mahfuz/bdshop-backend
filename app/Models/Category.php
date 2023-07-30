<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $casts  = [
        'active' => 'boolean'
    ];

    // private $discountLimit;
    // private $discountAmount;
    // private $discountRate;
    // private $minimumSpending;

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    // public function products()
    // {
    //     return $this->belongsToMany(Product::class);
    // }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    public function scopeRoot($query) {
        $query->whereNull('parent_id');
    }

    public function scopeActive($query) {
        $query->where('active', 1);
    }

    public function children()
    {
        // return $this->hasMany(Category::class, 'parent_id')->with(['meta:id,title,description,keywords,owner_id']);
        return $this->hasMany(Category::class, 'parent_id')->with([
            'meta:id,title,description,keywords,owner_id',
            'image:id,name,owner_id'
        ]);
    }

    public function meta()
    {
        return $this->morphOne(Meta::class, 'owner');
    }

    public function orderProducts()
    {
        return $this->hasManyThrough(OrderProduct::class, Product::class);
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'owner');
    }

    public function removeFromDB(string $type='image')
     {
        if ($type === 'icon') {
            return $this->update([
                'icon' => null
            ]);
        }
        return $this->image->delete();
     } 

    // public function add($data)
    // {
    //     return $this->create($data);
    // }

    // public function saveImageOf($image, $category)
    // {
    //     $category->image()->create([
    //         'name' => $image
    //     ]);
    //     return;
    // }

    // public function productPrice()
    // {
    //     $this->initialize();        
    // }

    // public function isDiscountAvailable($category)
    // {      
    //     // dump($category);
    //     // if ($category->discount === null) return false;
    //     if (!$this->isActiveDiscount($category)) return false;
    //     if (!$this->isDiscountDateValid($category)) return false;
    //     if ($category->discount->deduct->amount === null) return false;

    //     return true;           
    // }

    // public function isActiveDiscount($category) {
    //     // dump($this);
    //     return $category->discount->deduct->active ?? false;
    // }

    // public function isDiscountDateValid($category) {

    //     $check = Carbon::today();
    //     $from = $category->discount->deduct->starting;
    //     $to = $category->discount->deduct->ending;
        
    //     return ($check >= $from && $check <= $to ) ? true : false;
    // }
    
    // public function isMinimumSpendingAvailable() {
    //     return ($this->discount->deduct?->minimum_spending !== null) ? true : false;
    // }  
    
    // public function isDiscountLimitAvailable($category) {
    //     return ($category->discount->deduct->limit !== null) ? true : false;
    // }
   
    
    // public function setDiscountLimit() {
    //     $this->discountLimit = $this->discount->deduct->limit;      
    // }    
    
    // public function setDiscountRate() {    
    //     $this->discountRate = $this->discount->deduct->rate;
    // }

    // public function setDiscountAmount() {    
    //     if ($this->discount->deduct->amount_type === Deduct::AMOUNT_TYPE_PERCENTAGE) {

    //     return $this->discountAmount = $this->price*($this->discount->deduct->amount *0.01);
    //     }

    //     return $this->discountAmount = $this->discount->deduct->amount;
    // }

    // public function setMinimumSpending() {
    //     $this->minimumSpending = $this->discount->deduct?->minimum_spending;
    // }
    

    // public function initialize() {
    //     $this->setDiscountLimit();
    //     $this->setDiscountAmount();
    //     $this->setMinimumSpending();
    // }

}
