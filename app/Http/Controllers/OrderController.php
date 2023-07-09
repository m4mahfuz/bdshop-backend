<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    private $order;
    // private $user;

    // public function __construct(OrderService $order, AdminService $user)
    public function __construct(OrderService $order)
    {
        $this->order = $order;
        // $this->user = $user;
    }

    public function index()
    {
        // if ($this->user->isAuthorized(['super-admin', 'admin'])) {
        //     $order = Order::with([
        //         'user',
        //         'products:id,unit,unit_quantity',
        //         'products.featuredImage'
        // ])->orderBy('id')->paginate(3);  
        // } else {

            // $order = Order::where('user_id', Auth::user()->id)->with(['products:id,unit,unit_quantity',
            // 'products.featuredImage'])->orderBy('id', 'desc')->paginate(3);  
        $order = Order::where('user_id', Auth::user()->id)->with(['products'])->orderBy('id', 'desc')->paginate(3);  
        // }

        return OrderResource::collection($order);
        
    }

    public function show(Order $order)
    {
        // if (!$this->user->isAuthorized(['super-admin', 'admin'])) {
            $this->authorize('manage', $order);
        // }
        
        $order->load([            
            'coupon',
            // 'products:id,unit,unit_quantity',
            // 'products.featuredImage',                        
            // 'products.discount',            
            'products'
        ]);
        $order->products->each(function($product) {
            // $product->pivot->disputed;
            // $product->load('featuredImage');
            $product->pivot->load('disputed:id,status,reason,order_product_id');
        });

        return response(['data' => OrderResource::make($order)], Response::HTTP_OK);
    }

    public function invoice($uuid)
    {
        // $order = Order::where('uuid', $uuid)->first();
        $order = Order::detailsBy($uuid);
        
        $order->load([
            'shippingAddress:id,name,address_line,phone,city,order_id',
                'coupon',
                // 'user:id,name,email,phone',
                // 'products:id,unit,unit_quantity',

                // 'products.featuredImage',
                // 'shippingAddress.shipment:id,tracking_no,shipper_id,shipping_address_id',
                // 'shippingAddress.shipment.shipper'
        ]);
        // $order->products->each(function($product) {
        //     // $product->pivot->disputed;
        //     $product->pivot->load('disputed:id,status,reason,order_product_id');
        // });
        return response(['data' => OrderResource::make($order)], Response::HTTP_OK);
    }

    public function productsBy($uuid)
    {
        $order = Order::detailsBy($uuid);
        
        return response(['data' => $order->validProducts()], Response::HTTP_OK);
    }


    public function store(StoreOrderRequest $request)
    {
        return response([
            'data'=> OrderResource::make(
                $this->order->add($request)
            )
        ], Response::HTTP_CREATED);

        // return response([
        //     'data'=> $this->order->add($request)
        // ], Response::HTTP_CREATED);
    }

    // public function cancel(Order $order)
    // {
    //     $order->update(['status' => 'Cancelled']);

    //     // move all order_products items of this order to order_product_archive
    //     $order->moveToArchive($order->products, 'Cancelled');
        
    //     //create logs
    //     $order->orderLogs()->create([
    //         'status' => $order->status,
    //     ]);
    // }

    // public function cancelItems(Order $order, Request $request)
    // {
    //     $validated = $request->validate([
    //         'items' => 'required|array',
    //     ]);
    //     if ( count($validated['items']) === count($order->products) ) {
    //         $this->cancel($order);
    //         return;      
    //     } 
    //     $order->update(['status' => 'Cancelled Partially']);
    //         // move only the cancelled order_products items of this order to order_product_archive
      
    //     //create logs
    //     $order->orderLogs()->create([
    //         'status' => $order->status,
    //     ]);

    //     $order->moveToArchive($validated['items'], 'Cancelled');
    // }
}
