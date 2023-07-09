<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
// use App\Models\Product;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\Response;

class CategoryProductController extends Controller
{
    
    public function index(Category $category)
    {        
        // $products = $category->products()->active()->get(['id', 'name', 'price', 'unit_quantity', 'unit',]);

        $products = $category->products()->active()->get(['id', 'name', 'slug','price', 'unit_quantity', 'unit', 'inventory_id', 'featured_image_id'])->load(['inventory:id,sku,quantity', 'featuredImage:id,name']);


        return response([
            'data' => $products
        ], Response::HTTP_OK);
        
    }
}