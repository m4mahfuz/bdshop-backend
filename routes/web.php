<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/phpinfo', function() {
    return phpinfo();
});

Route::get('/mailable', function () {
    $invite = App\Models\Invite::find(1);
    // App\Events\OrderStatusUpdated::dispatch($order);
    return new App\Mail\InviteCreated($invite);
    // $order->load([
//             'shippingAddress:id,name,address_line,phone,city,order_id',
//                 'coupon',
//                 // 'user:id,name,email,phone',
//                 // 'products:id,unit,unit_quantity',

//                 // 'products.featuredImage',
//                 // 'shippingAddress.shipment:id,tracking_no,shipper_id,shipping_address_id',
//                 // 'shippingAddress.shipment.shipper'
//         ]);
//     $path = public_path('storage/pdf/invoices/');
//     // $path = url('storage/pdf/invoices/');
//     // return $path;
//     $products = $order->validProducts();
//     $pdf = Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', ['order' => $order, 'products' => $products]);
//     // $fileName =  $post['title'] . '.' . 'pdf' ;
//     $fileName = "invoice_{$order->uuid}.pdf" ;
//     $pdf->save($path . '/' . $fileName);
//     // return $pdf->download('demo.pdf');

//     // dd($order->shippingAddress->name);
//     // return $order->shippingAddress;
//     return view('pdf.invoice', ['order' => $order, 'products' => $products]);

//     // return new App\Mail\OrderShipped($order);
//     // return $order->validProducts();

//     // $disputed = [];
    
//     // foreach ($order->products as $product) {
//     //     // code...
//     //     if ($product->pivot->disputed !== null) {
//     //         $disputed[] = $product->pivot->disputed;
//     //     }
//     // }
//     // return $disputed;

//     // $order->load([
//     //         'shippingAddress:id,name,address_line,phone,city,order_id',
//     //         'coupon',
//     //         'user:id,name,email,phone',
//     //         'products:id,unit,unit_quantity',
//     //     ]);
//     // return $order;
//     // return App\Http\Resources\OrderResource::make($order);
//     $user= App\Models\User::find($order->user_id);
//     // return new App\Mail\OrderShipped($order);
//     Illuminate\Support\Facades\Mail::to($user)->send(new App\Mail\OrderShipped($order));
//     return 'send';
    
//     return new App\Mail\OrderShipped($order);
});

Route::get('/paymentable', function () {
    // $config = config('payment.client');
    // $url = $config['url'];

   
    $order = App\Models\Order::find(27);
    // // dd ($order->payment);
    App\Events\PaymentStatusUpdated::dispatch($order->payment);
    return 'send';
       
    $payment = App\Models\Payment::find(19);
    // $payment->load([
    //    'order'     
    // ]);
    
    return new App\Mail\PaymentReceived($payment);
    // return $order->validProducts();

    // $disputed = [];
    
    // foreach ($order->products as $product) {
    //     // code...
    //     if ($product->pivot->disputed !== null) {
    //         $disputed[] = $product->pivot->disputed;
    //     }
    // }
    // return $disputed;

    // $order->load([
    //         'shippingAddress:id,name,address_line,phone,city,order_id',
    //         'coupon',
    //         'user:id,name,email,phone',
    //         'products:id,unit,unit_quantity',
    //     ]);
    // return $order;
    // return App\Http\Resources\OrderResource::make($order);
    $user= App\Models\User::find(1);
    // return new App\Mail\OrderShipped($order);
    Illuminate\Support\Facades\Mail::to($user)->send(new App\Mail\PaymentReceived($payment));
    return 'send';
    
    return new App\Mail\OrderShipped($order);
});
Route::get('/invoice', function () {
    
    $order = App\Models\Order::find(10);

    $order->load([
            'shippingAddress:id,name,address_line,phone,city,order_id',
            'coupon',      
    ]);
    
    $path = public_path('storage/pdf/invoices/');
    // $path = url('storage/pdf/invoices/');
    // return $path;
    $products = $order->validProducts();

    $pdf = Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', ['order' => $order, 'products' => $products]);

    $fileName = "invoice_{$order->uuid}.pdf" ;

    $pdf->save($path . '/' . $fileName);

    // return view('pdf.invoice', ['order' => $order, 'products' => $products]);

    $user= App\Models\User::find($order->user_id);
    // return new App\Mail\OrderShipped($order);
    Illuminate\Support\Facades\Mail::to($user)->send(new App\Mail\OrderDelivered($order));
    return 'send';
    
    // return new App\Mail\OrderShipped($order);
});