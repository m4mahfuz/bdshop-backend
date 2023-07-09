<?php

namespace App\Http\Controllers\Admin;

use App\Events\OrderStatusUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderLogResource;
use App\Http\Resources\OrderProductResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ShipmentResource;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{    
    public function index()
    {
        // if ($this->user->isAuthorized(['super-admin', 'admin'])) {
        //     $order = Order::with([
        //         'user',
        //         'products:id,unit,unit_quantity',
        //         'products.featuredImage'
        // ])->orderBy('id')->paginate(3);  
        // } else {

            $order = Order::with([
                'shippingAddress:id,name,address_line,phone,city,order_id',
                'coupon',
                'user:id,name,email,phone',
                // 'products:id,unit,unit_quantity',
                // 'products.featuredImage',
                'shippingAddress.shipment:id,tracking_no,shipper_id,shipping_address_id',
                'shippingAddress.shipment.shipper'
            ])->orderBy('id', 'desc')->paginate(3);  
        // }

        return OrderResource::collection($order);
    }

    public function ordersBy(string $status)
        {
            $order = Order::where('status', ucfirst($status))->with([
                'shippingAddress:id,name,address_line,phone,city,order_id',
                'coupon',
                'user:id,name,email,phone',
                // 'products',
                // 'products:id,unit,unit_quantity',

                // 'products.featuredImage',
                'shippingAddress.shipment:id,tracking_no,shipper_id,shipping_address_id',
                'shippingAddress.shipment.shipper'
            ])->orderBy('id', 'desc')->paginate(3);  
            
            
            return OrderResource::collection($order);
        }

    public function show(Order $order)
    {        
        $order->load([
            'coupon',
            // 'products:id,unit,unit_quantity',
            // 'products.featuredImage',                        
            // 'products.discount',            
        ]);
        // $order->products->each(function($product) {
        //     // $product->pivot->disputed;
        //     $product->pivot->load('disputed:id,status,order_product_id');
        // });
        return OrderResource::make($order);
    }

    public function updateOderStatus(Request $request, Order $order)
    {
        $attributes = $request->validate([
            'status' => 'required|string',
            'shipper_id' => 'sometimes|required|exists:shippers,id',
            'tracking_no' => 'sometimes|required'

        ]);

        DB::transaction(function() use($request, $order) {

            if ($request->has(['shipper_id', 'tracking_no'])) {
                $order->shippingAddress->shipment()->updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'shipper_id' => $request->input('shipper_id'),
                        'tracking_no' => $request->input('tracking_no')
                    ]
                );
            }

            // order status updte
            $order->update(['status' => $request->input('status')]);

            // Payment
            if ($request->input('status') === 'Delivered' && $order->payment_method === Payment::PAYMENT_METHOD_COD) {
                Payment::for($order);
            }
            
            OrderStatusUpdated::dispatch($order);
        });        

        if ($request->input('status') === 'Shipped') {

            return response([
                'data' => ShipmentResource::make($order->shippingAddress->load(['shipment.shipper']))], Response::HTTP_OK);
        }

        return ['data' => 'Updated Successfully!'];
    }

    public function orderLogsBy(Order $order)
    {
        return response([
            'data' => OrderLogResource::collection($order->orderLogs)
        ], Response::HTTP_OK);
    }

    public function productsBy(Order $order)
    {
        if ($order->status === 'Cancelled' || $order->status === 'Returned') {
            return response(['data' => $order->disputedProducts()], Response::HTTP_OK);
        }
        
        // return response(['data' => $order->validProducts()], Response::HTTP_OK);
       return response(['data' => OrderProductResource::collection($order->validProducts())], Response::HTTP_OK);
    }

}
