<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Deduct;
use App\Models\Discount;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tests\Setup\UserRole;
use Tests\Utilities\AdminRole;
use function Pest\Laravel\{getJson, postJson};

uses(
    RefreshDatabase::class, 
    AdminRole::class
);


beforeEach(function () {
    $this->adminUser = UserRole::setAs('super_admin')->create();       
});


it('should return all categories', function() { 

    $category = Category::factory()->create();


    Category::factory()->count(3)->create(['parent_id' => $category->id]);

    Category::factory()->count(2)->create();
    
    $response = getJson(route('categories.index'))
        ->assertStatus(Response::HTTP_OK)
        ->json('data');

    $this->assertDatabaseCount('categories', 6);

});

it('should return a category', function() {
    
    $category = Category::factory()->create();

    // $product = Product::factory()
    //                     ->has(Inventory::factory(['discount_id' => null])->count(3))
    //                     ->count(3)
    //                     ->hasAttached($category)
    //                     ->create();
    
    $response = getJson(
        route('categories.show', [ 'category' => $category['slug'] ]) 
    )->assertStatus(Response::HTTP_OK)
    ->json('data');    
    
});

// it('should return a category including related products with discount', function() {
    
//     $category = Category::factory()->create(['description' =>'Category for Fruits']);

//     $discount = Discount::factory()->hasDeduct()->create();

//     $product = Product::factory()
//                         ->has(Inventory::factory(['discount_id' => $discount->id])->count(3))
//                         ->count(3)
//                         ->hasAttached($category)
//                         ->create();

//     $response = getJson(
//         route('categories.show', [ 'category' => $category['slug'] ]) 
//     )->assertStatus(Response::HTTP_OK)
//     ->json('data');

//     // dump($response);

//     expect($category)
//         ->id->toBe($response['id'])
//         ->slug->toBe($response['slug'])
//         ->name->toBe($response['name'])
//         ->description->toBe('Category for Fruits');
// });


it('shoud not allow regular user to create a category', function() {
    $user = User::factory()->create();

    $response = actingAs($user)->postJson(
        route('categories.store'), [
            'name' => 'Fruits',
            'slug' => 'Fruits',
            'description' => 'description',
            'parent_id' => null
        ]
    )->assertStatus(Response::HTTP_UNAUTHORIZED)
    ->json('data');    
});


it('should allow only super_admin or admin to  create a category', function() {

    $response = actingAs($this->adminUser)->postJson(
        route('categories.store'), [
            'name' => 'Fruits',
            'slug' => 'Fruits',
            'description' => 'Category for Fruits',
            'parent_id' => null,            
        ]
    )->assertStatus(Response::HTTP_CREATED)
    ->json('data');

    $category = getJson(
        // route('categories.show', [ 'category' => $response['id'] ]) 
        route('categories.show', [ 'category' => $response['slug'] ]) 
    )->json('data');

    expect($category)
        ->id->toBe($response['id'])
        ->slug->toBe($response['slug'])
        ->name->toBe('Fruits')
        ->description->toBe('Category for Fruits')
        ->productCount->toBe(0);
});


it('should return 422 if name is missing', function ($name) {
    actingAs($this->adminUser)->postJson(
        route('categories.store'), [
            'name' => $name,
            'slug' => $name,
            'description' => 'description',
            'parent_id' => null
        ]
    )
    ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([
    '',
    null
]);


it('should return 422 if name is not unique', function () {
    
    Category::factory(['name' => 'Fruits'])->create();

    actingAs($this->adminUser)->postJson(
        route('categories.store'), [
            'name' => 'Fruits',
            'slug' => 'Fruits',
            'description' => 'description',
            'parent_id' => null
        ]
    )
    ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});
