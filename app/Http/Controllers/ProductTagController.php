<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagResource;
use App\Models\Product;
use Symfony\Component\HttpFoundation\Response;

class ProductTagController extends Controller
{    

    public function index(Product $product)
    {
        return response([
            'data' => TagResource::collection($product->tags()->get())           
        ], Response::HTTP_OK);    
    }
    
    
}
