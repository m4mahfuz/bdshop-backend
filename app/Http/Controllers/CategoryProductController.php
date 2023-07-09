<?php

namespace App\Http\Controllers;

// use App\Http\Resources\CategoryResource;
use Illuminate\Database\Eloquent\Collection;
// use App\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;

class CategoryProductController extends Controller
{
    // public function index(Category $category)
    // {
    //     // $categoryProducts = $category->products->load([
    //     //     // 'products.featuredImage',
    //     //     // 'products.discount.deduct'
    //     //     'featuredImage',
    //     //     'discount.deduct'
    //     // ]);
    //     // return [
    //     //     'data' => ProductResource::collection($categoryProducts),            
    //     // ];

    //     $categoryProducts = $category->products()->with([
    //         'featuredImage',
    //         'discount.deduct'
    //     ])->orderBy('slug')->cursorPaginate();

    //     return new ProductCollection($categoryProducts);
    // }

    // public function index(Category $category)
    // {

    //     if ($category->parent_id === null && $category->children->count() > 0) {
            
    //         $products = [];
                            
    //         foreach ($category->children as $child) {
    //             $products = array_merge($products,
    //                 $child->products()->with([
    //                     'featuredImage:id,name',
    //                     'discount.deduct'
    //                 ])->get()->all()
    //             );
    //         }
    //         $col = (new Collection($products))->paginate(10);
    //         return $col;
    //     }

    //     $categoryProducts = $category->products()->with([
    //         'featuredImage:id,name',
    //         'discount.deduct'
    //     ])->orderBy('slug')->cursorPaginate();

    //     return new ProductCollection($categoryProducts);
    // } 

    public function index(Category $category)
    {        
        /********************
        $products = Product::whereHas('category', function(Builder $q) {
            $q->where('parent_id', 2);
        })->cursorPaginate(10);
        **************/
        $productsCount = 0;

        if ($category->parent_id === null && $category->children->count() > 0) {     

            $productsBuild = Product::whereHas('category', function(Builder $q) use ($category) {
                $q->where('parent_id', $category->id);
            });

            $productsCount = $productsBuild->count();

            $products = $productsBuild->with([
                'category:id,name,slug',
                'discount.deduct',
                'featuredImage:id,name',
            ])
            ->orderBy('slug')->cursorPaginate(15);

        } else {        
        
            $productsBuild = Product::whereHas('category', function(Builder $q) use ($category) {
                    $q->where('category_id', $category->id);
            });

            // dd($productsBuild);
            $productsCount = $productsBuild->count();

            $products = $productsBuild->with([
                'category:id,name,slug',
                'discount.deduct',
                'featuredImage:id,name',
            ])->orderBy('slug')->cursorPaginate(15);

        }

        return (new ProductCollection($products))->additional(
            [
                'meta' => [
                    'totalProducts' => $productsCount, //$productsBuild->count(),
                ]
            ]
        );
    }
}