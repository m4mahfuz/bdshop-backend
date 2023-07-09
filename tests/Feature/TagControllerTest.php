<?php

namespace Tests\Feature;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\Setup\UserRole;
use Tests\TestCase;
use Tests\Utilities\AdminRole;
use function Pest\Laravel\{ getJson, postJson, deleteJson };

uses(
    RefreshDatabase::class, 
    AdminRole::class
);

beforeEach(function () {
    $this->adminUser = UserRole::setAs('super_admin')->create();       
});


it('should create a tag', function()
{
    
    $response = actingAs($this->adminUser)->postJson(
        route('tags.store'),
        [
            'name' => 'Popular',
            'slug' => 'Popular',
        ]
    )->assertStatus(Response::HTTP_CREATED)
    ->json('data');  
    dump($response);
    $this->assertDatabaseHas('tags', [
        'name' => $response['name']
    ]);

 });

it('should show all tags', function() {

    Tag::factory()->count(5)->create();

    $response = getJson(route('tags.index'))
        ->assertStatus(Response::HTTP_OK)
        ->json('data');

    $this->assertDatabaseCount('tags', 5);

}); 

it('should delete a tag', function() {

    $tag = Tag::factory()->create();

   actingAs($this->adminUser)->
       deleteJson(route('tags.destroy', ['tag' => $tag->slug])
        )->assertStatus(Response::HTTP_NO_CONTENT);

   $this->assertDatabaseCount('tags', 0);

});