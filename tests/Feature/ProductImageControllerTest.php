<?php

namespace Tests\Feature;

// use App\Models\Office;
// use App\Models\User;
// use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

it('should upload image and store it under product', function()
{
    Storage::fake();

    $inventory = Inventory::factory()->create();

    $product = Product::factory()->for($inventory)->create();

    $response = actingAs($this->adminUser)->postJson(
        route('products.images.store', ['product' => $product->slug]),
        [
            'image' => UploadedFile::fake()->image('image.jpg'),
            'featured_image' => true
        ]
    )->assertStatus(Response::HTTP_CREATED)
    ->json('data');  
   
    // Storage::disk('public')->assertExists('image.jpg');
   
    Storage::assertExists(
       $response['path']
    );

    $this->assertDatabaseHas('products', [
        'featured_image_id' => $response['id']
    ]);

 });

it('should delete an image', function() {

    Storage::put('/products/images/product_image.jpg', 'empty');

        $inventory = Inventory::factory()->create();

        $product = Product::factory()->for($inventory)->create();

        $product->images()->create([
            'path' => 'image.jpg'
        ]);

        $image = $product->images()->create([
            'path' => 'product_image.jpg'
        ]);


            // ->deleteJson("/products/{$product->id}/images/{$image->id}")
        $response = actingAs($this->adminUser)->deleteJson(
            route('products.images.destroy', [
                'product' => $product->slug,
                'image' => $image->id,
            ])
        )->assertStatus(Response::HTTP_NO_CONTENT);

        // $response->assertOk();

        $this->assertModelMissing($image);

        Storage::assertMissing('product_image.jpg');
}); 

    