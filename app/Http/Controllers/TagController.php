<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TagController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware([
    //         'auth:admin',
    //         'type:super-admin,admin'
    //     ])->except('index', 'show');
    // }

                // 'discount.deduct',
    public function show(Tag $tag)
    {
        $products = $tag->products()->with([
                // 'category:id,name,slug',
                'featuredImage:id,name',
            ])->paginate(12);

        return (new ProductCollection($products));
    }

   

}