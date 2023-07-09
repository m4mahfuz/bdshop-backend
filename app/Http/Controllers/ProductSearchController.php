<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductSearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        // $products = Product::where('name', 'like', "%$query%")->get();
        $products = Product::where('name', 'like', "%$query%")->with([
            'discount.deduct',
            'category:id,name,slug', 
            'featuredImage:id,name'
        ])->orderBy('slug')->cursorPaginate(10);

        // return response()->json($products);
        return new ProductCollection($products);
    }
    
}
