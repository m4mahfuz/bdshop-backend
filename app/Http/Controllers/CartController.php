<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Services\CartService;
// use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    private $cart;

    public function __construct(CartService $cart)
    {
        $this->middleware([
            'auth',
            // 'role:super_admin,admin'
        ]);//->except('index', 'show');        
        
        $this->cart = $cart;
    }
    
    
    public function index()
    {
        return [
            'data' => CartResource::collection(Cart::all())
        ];
    }

 
    public function store(StoreCartRequest $request)
    {
        return response([
           'data' => CartResource::make(
                $this->cart->add($request)
            )
        ], Response::HTTP_CREATED);
    }
    

    public function show(Cart $cart)
    {
        // return $cart->product->productPrice();
    }

    public function update(StoreCartRequest $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $cart = Cart::where('product_id', $id)->first();
        $cart?->delete();
        return response([], Response::HTTP_NO_CONTENT);
    }
}
