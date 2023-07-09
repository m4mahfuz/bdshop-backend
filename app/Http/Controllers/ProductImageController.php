<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImageResource;
use App\Http\Resources\ProductImageResource;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ProductImageController extends Controller
{

    public function __construct()
    {
        $this->middleware([
            'auth:sanctum',
            'type:super-admin,admin'
        ])->except('index', 'show');
    }

    
    public function index()
    {
        //
    }

    public function show(Product $product)
    {
        // $product->load('categories:id,name');
        // return [
        //     'data' => ProductResource::make($product->load([     
        //             'images:id,name,owner_id',
        //         ])
        //     )
        // ];            
        // return response([
        //     'data' => ImageResource::collection($product->images()->get())           
        // ], Response::HTTP_OK);    
        // $product->load(['images', 'featuredImage']);
        return response([
            'data' => ProductImageResource::make($product->load(['images', 'featuredImage']))           
        ], Response::HTTP_OK);    
    }
    

    public function store(Product $product)
    {
        request()->validate([
            // 'image' => ['file', 'max:5000', 'mimes:jpg,png'],
            'images' => 'required|array|min:1',
            'images.*' => 'required|string',
            // 'featured_image' => 'nullable|sometimes|boolean'
        ]);

        $images = request()->input('images');                
        $imagesArray = [];
        $imagesArray = 
        DB::transaction(function() use($product, $images) {

            //storing in disk
            /***
            Storing to disk is being done by ImageController store()  
            */

            // $path = request()->file('image')->storePublicly('/images/products');

            // storing the image path` in db images table
            foreach ($images as $image) {
               $imageSaved = $product->images()->create([
                    'name' => $image
                ]);

               $imagesArray[] = (object) [
                    'id' => $imageSaved->id,
                    'name' => $imageSaved->name
               ];
            }

            // if (request()->input('featured_image') === true) {
            //    $this->update($image);
            // }
            return $imagesArray;
        });        

        return response([
            'data' => $imagesArray
        ], Response::HTTP_CREATED);

    }

    public function update(Image $image)
    {

        $image->owner()->update([
            'featured_image_id' => $image->id
        ]);
        
        return response([
            'data' => ImageResource::make($image)
        ], Response::HTTP_OK);
    }
    
    public function destroy(Product $product, Image $image)
    {
          throw_if($product->images()->count() == 1,
            ValidationException::withMessages(['image' => 'Cannot delete the only image.'])
        );


        throw_if($product->featured_image_id == $image->id,
            ValidationException::withMessages(['image' => 'Cannot delete the featured image.'])
        );  

        $largeImgagePath = "images/products/large/{$image->name}";            
        $mediumImgagePath = "images/products/medium/{$image->name}";            
        $smallImgagePath = "images/products/small/{$image->name}";            

        // Storage::delete($image->path);
        $image->removeImagesFromStorage($largeImgagePath);
        $image->removeImagesFromStorage($mediumImgagePath);
        $image->removeImagesFromStorage($smallImgagePath);
        // From DB
        $image->delete();

        return response([], Response::HTTP_NO_CONTENT);
    }
}
