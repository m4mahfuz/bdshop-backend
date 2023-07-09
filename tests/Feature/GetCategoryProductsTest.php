<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Deduct;
use App\Models\Discount;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\getJson;

uses(
    RefreshDatabase::class, 
    // AdminRole::class
);

it('should return all products for a category', function () {

    $discount = Discount::factory()->has(Deduct::factory())->create();

    $category = Category::factory()->create();

    Product::factory()->count(5)
            ->hasAttached($category)
            ->create(['discount_id' => $discount->id]);

    Category::factory()->count(10)->create();
    
    Product::factory()                        
            ->count(3)                       
            ->hasAttached(Category::factory()->count(2))
            ->create();
    
    $products = getJson(route('category/products.index', ['category' => $category->slug]))->json('data');
    
    expect($products)->toHaveCount(5);
});