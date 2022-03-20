<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tests\Setup\UserRole;
use Tests\Utilities\AdminRole;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

uses(
    RefreshDatabase::class, 
    AdminRole::class
);


beforeEach(function () {
    $this->adminUser = UserRole::setAs('super_admin')->create();       
});

it('should allow a guest to get a category info', function() {

    $category = Category::factory()->create(['name' => 'Fruits']);
    
    $response = getJson(
        route('categories.show', ['category' => $category->id]) 
    )->assertStatus(Response::HTTP_OK)
    ->json('data');
    
    // dump($response); 

    expect($response)
        ->id->toBe($category->id)
        ->name->toBe('Fruits')
        ->description->toBe($category->description)
        ->productCount->toBe(0);
});


it('shoud not allow regular user to create a category', function() {

    $user = User::factory()->create();

    $response = actingAs($user)->postJson(
        route('categories.store'), 
        [
            'name' => 'Fruits',
            'description' => 'description'
        ]
    )
    ->assertStatus(Response::HTTP_UNAUTHORIZED)
    ->json('data');    
});


it('should allow only super_admin or admin to  create a category', function() {

    $response = actingAs($this->adminUser)->postJson(
        route('categories.store'), 
        [
            'name' => 'Fruits',
            'description' => 'Category for Fruits'
        ]
    )
    ->assertStatus(Response::HTTP_CREATED)
    ->json('data');
    
    $category = getJson(
        route('categories.show', ['category' => $response['id']]) 
    )->json('data');
    
    // dump($category); 

    expect($category)
        ->id->toBe($response['id'])
        ->name->toBe('Fruits')
        ->description->toBe('Category for Fruits')
        ->productCount->toBe(0);
});


it('should return 422 if name is missing', function ($name) {
    actingAs($this->adminUser)->postJson(route('categories.store'), [
        'name' => $name,
        'description' => 'description'
    ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([
    '',
    null
]);


it('should return 422 if name is not unique', function () {
    
    Category::factory(['name' => 'Fruits'])->create();

    actingAs($this->adminUser)->postJson(route('categories.store'), [
        'name' => 'Fruits',
        'description' => 'description'
    ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});
