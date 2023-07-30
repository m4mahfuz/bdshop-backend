<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Libraries\DiscountException;
use App\Models\Coupon;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class ProductController extends Controller
{
    private $product;

    public function __construct(ProductService $product)
    {
        $this->middleware([
            'auth:admin',
            'type:super-admin,admin'
        ])->except('index', 'show', 'apply', 'toggleWishlist');        

        $this->product = $product;
    }    
    
    public function index()
    {        
        $products = Product::with([
            'discount.deduct',
            'category:id,name,slug', 
            'featuredImage:id,name'
        ])->orderBy('slug')->cursorPaginate(15);//->get();
        
        return (new ProductCollection($products))->additional(
            [
                'meta' => [
                    'totalProducts' => Product::count(), //
                ]
            ]
        );
    }
    
    public function store(StoreProductRequest $request)
    {        
        return response([
            'data' => ProductResource::make($this->product->add($request))
        ], Response::HTTP_CREATED);
    }
    
    public function show(Product $product)
    {
        // $product->load('categories:id,name');
        return [
            'data' => ProductResource::make($product->load([
                    'discount.deduct',
                    'category:id,name,slug',
                    'inventory:id,quantity,sku',
                    'tags:id,name,slug',
                    'images:id,name,owner_id',
                    'meta:id,title,description,keywords,owner_id'
                ])
            )
        ];    
    }
    
    public function update(StoreProductRequest $request, Product $product)
    {        
        return response([
            'data' => ProductResource::make($this->product->update($request, $product))
        ], Response::HTTP_OK);
    }

    public function destroy(Product $product)
    {
        $product->images()->each(function ($image) {
            Storage::delete($image->path);

            $image->delete();
        });

        $product->delete();
        return response([], Response::HTTP_NO_CONTENT);
    }

    // public function apply(Request $request, Coupon $coupon)
    public function apply(Request $request)
    {
        // return \App\Models\Cart::items();

        $attributes = $this->validateRequest($request);

        $code = $attributes['code'];
        $amount = $attributes['amount'];


        // $couponInfo = $coupon->where('code', $code)->first();
        $couponInfo = Coupon::where('code', $code)->first();

        $givenDate = date("Y-m-d");

        $response = $this->product->checkValidityOf($couponInfo, $amount, $givenDate);

        if ($response === true) {
            
            $discountAmount = $this->product->getDiscountAmount($couponInfo, $amount);

            if ($discountAmount > 0) {                

            $this->storeInSession($code, $discountAmount);

                return response([
                    'data' => [
                        'discount_amount' => round($discountAmount),
                        'total_amount' => $amount,
                        // 'session_code' => session('code')
                    ] 
                ], Response::HTTP_OK);

            }
            $response = "Discount not available!";
        }

        return response([
            'errors' => [
                'code' => [$response]
            ]
            // 'coupon' => [$response]
        ], Response::HTTP_NOT_FOUND);
    }

    public function toggleWishlist(Request $request)
    {
        /*$wishlistCount = $product->wishlistByUser()? $product->wishlistByUser()->count() : 0;

        if ($wishlistCount === 0) {

            $product->wishlists()->create([
                'user_id' => Auth::user()->id,
                'product_id' => $request->input('product_id')
            ]);
            
            $message = 'Added to favourites successfully!';
            
            return response([
                'data' => ['message' => $message, 'count' => ($wishlistCount + 1)]
            ], Response::HTTP_CREATED);
        }

        //delete
        $product->wishlistByUser()?->delete();
        
        $message = 'Removed from favourites successfully!';
            
            return response([
                'data' => ['message' => $message, 'count' => ($wishlistCount - 1)]
            ], Response::HTTP_NO_CONTENT);*/

        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::find($request->input('product_id'));

        $wishlistCount = $this->product->toggleWishlist($product);

        if ($wishlistCount === 1) {
            $message = 'Removed from favourites successfully!';
            
            return response([
                'data' => ['message' => $message, 'count' => ($wishlistCount - 1)]
            ], Response::HTTP_OK);

        }

        $message = 'Added to favourites successfully!';
            
        return response([
            'data' => ['message' => $message, 'count' => 1]
        ], Response::HTTP_CREATED);   

        // $message = 'Removed from favourites successfully!';
            
        // return response([
        //     'data' => ['message' => $message, 'count' => ($wishlistCount - 1)]
        // ], Response::HTTP_OK);

    }

    public function storeInSession($code, $discountdAmount)
    {
        session([
            'couponCode' => $code,
            'couponDiscountdAmount' => round($discountdAmount)
        ]);
    }

    protected function validateRequest(Request $request)
    {
        return $request->validate([
            'code' => 'required|string|exists:coupons,code',
            'amount'   => 'required'
        ]);
    }
}
