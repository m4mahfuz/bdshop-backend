<?php

namespace App\Models;

use App\Http\Resources\ProductResource;
use App\Mail\OrderDelivered;
use App\Mail\OrderReceived;
use App\Mail\OrderShipped;
use App\Models\ShippingType;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    protected $casts = [
        'created_at' => 'immutable_datetime'
    ];

    // public function coupon()
    // {
    //     return $this->belongsTo(Coupon::class);
    // }

    // public function additional()
    // {
    //     return $this->hasOne(OrderAditional::class);
    // }

    public function coupon()
    {
        return $this->hasOne(OrderCoupon::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot(
                'id',
                'user_id',
                // 'product_id',
                'name',
                'price', 
                'discounted_price', 
                'discount_type', 
                'quantity',
                'additional_quantity',
            )->using(OrderProduct::class)
            ->withTimestamps();    
    }

    // public function orderProductArchives()
    // {
    //     return $this->hasMany(OrderProductArchive::class);
    // }

    // public function status()
    // {
    //     return $this->belongsTo(OrderStatus::class);
    // }

    public function orderLogs()
    {
        return $this->hasMany(OrderLog::class);
    }

    public function createLog()
    {
        return $this->orderLogs()->create([
             'status' => $this->status,
        ]);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }


    public function shippingAddress()
    {
        return $this->hasOne(ShippingAddress::class);
    }

    public function shipping()
    {
        return $this->belongsTo(Shipping::class);
    }
    
    public function disputedProducts() {
        $products = [];
    
        foreach ($this->products as $product) {
            if ($product->pivot->disputed !== null) {
                $products[] = $product;//$product->pivot->products;
            }
        }
        // return ProductResource::collection($products);
        return $products;
    }

    public function validProducts() {
        $products = [];
    
        foreach ($this->products as $product) {
            if ($product->pivot->disputed === null) {
                $products[] = $product;//$product->pivot->products;
            }
        }
        return $products;
    }

    // public function moveToArchive($items, $status='')
    // {
    //     foreach ($items as $item) {
    //         $this->orderProductArchives()->create([
    //             'user_id' => $item->pivot->user_id,
    //             'product_id' => $item->pivot->product_id,
    //             'name' => $item->pivot->name,
    //             'price' => $item->pivot->discounted_price ?? $item->pivot->price ,
    //             'quantity' => $quantity,
    //             'status' => $status
    //         ]);
    //     }           
    // }

    public function firstProduct()
    {
        return $this->products()->first();
    }

    public function deliveryEarliest()
    {
        $orderDate = $this->created_at;
        $timeToAdd = $this->shipping->shippingTypes()->whereType($this->shipping_type)->first()->delivery_time_min;
        $afterAdding = $orderDate->addDay($timeToAdd);

        return \Carbon\Carbon::parse($afterAdding)->format('d M');
    }

    public function deliveryLatest()
    {
        $orderDate = $this->created_at;
        $timeToAdd = $this->shipping->shippingTypes()->whereType($this->shipping_type)->first()->delivery_time_max;
        $afterAdding = $orderDate->addDay($timeToAdd);

        return \Carbon\Carbon::parse($afterAdding)->format('d M');
    }

    public function deliveryPeriod()
    {
        $shippingType = $this->shipping->shippingTypes()->whereType($this->shipping_type)->first();
        $period = "{$shippingType->delivery_time_min}-{$shippingType->delivery_time_max}";
        if ($shippingType->type === ShippingType::SHIPPING_TYPE_STANDARD) {
            return "{$period} days";
        }
        return "{$period} hours";
    }

    public function shippingType()
    {
        return ($this->shipping_type === ShippingType::SHIPPING_TYPE_STANDARD) ? 'Standard' : 'Express';
    }

    public function mailToUser()
    {
        match ($this->status) {
            'Received' => Mail::to($this->user)->send(new OrderReceived($this)),
            'Shipped' => Mail::to($this->user)->send(new OrderShipped($this)),
            // 'Delivered' => Mail::to($this->user)->send(new OrderDelivered($order)),
            'Delivered' => $this->delivered(),
            default => null,
        };
    }

    public function delivered()
    {
        $this->load([
            'shippingAddress:id,name,address_line,phone,city,order_id',
            'coupon',      
        ]);
        $products = $this->validProducts();

        $path = public_path('storage/pdf/invoices/');

        $pdf = Pdf::loadView('pdf.invoice', ['order' => $this, 'products' => $products]);

        $fileName = "invoice_{$this->uuid}.pdf" ;

        $pdf->save($path . '/' . $fileName);

        Mail::to($this->user)->send(new OrderDelivered($this));
    }

    public function deliveredOn()
    {
        return ($this->status === 'Delivered') ? 
        $this->updated_at->format('F j, Y') : null;
    }

    public function paymentMethod()
    {
        return ($this->payment_method === Payment::PAYMENT_METHOD_COD) ? 'COD' : 'Prepaid';
    }

    public static function detailsBy($uuid)
    {
        return Order::where('uuid', $uuid)->first();
    }

    // public function updateStatusTo($status)
    // {
    //     return $this->update(['status' => $status]);
    // }

    // public function savePayment()
    // {
    //     return $this->payment()->firstOrCreate([
    //         'transaction_id' => $this->uuid,
    //         'amount' => $this->net_total, 
    //         'method' => $this->payment_method //$this->convertToReadable($this->payment_method),              
    //     ]);        
    // }

    // public function estimatedDeliverDate()
    // {
    //     $order = $this->orderLogs()->where('status', 'Received')->first(); 
    //     return new Carbon($order->dateTime)->addDay(5);
    // }

    public function getNetTotal()
    {
        $this->net_total;
    }

    public function convertToReadable($statusValue)
    {
        return collect([
         '1' => 'cod', 
         '2' => 'prepaid',
        ])->first(function($value, $key) use ($statusValue) {
            return $key === $statusValue;
        });
    }

    public function getData()
    {
        $post_data = array();
       
        $post_data['total_amount'] = $this->net_total;
        $post_data['tran_id'] = $this->uuid; //"ABC-".uniqid();
        $post_data['currency'] = "BDT";

        // $post_data['success_url'] = url('/')."/api/payment/success";
        // $post_data['fail_url'] = url('/')."/api/payment/fail";
        // $post_data['cancel_url'] = url('/')."/api/payment/cancel ";

        # EMI STATUS
        $post_data['emi_option'] = 0;

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $this->user->name;
        $post_data['cus_email'] =$this->user->email;
        $post_data['cus_phone'] = $this->user->phone;
        $post_data['cus_add1'] = $this->user->defaultShippingAddress->address->address_line;
        $post_data['cus_add2'] ="";
        $post_data['cus_city'] = $this->user->defaultShippingAddress->address->city;
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = $this->user->defaultShippingAddress->address->postal_code;
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['shipping_method'] = "YES";
        $post_data['num_of_item'] = $this->products()->count();
        $post_data['ship_name'] = $this->user->defaultShippingAddress->address->address_line;;
        $post_data['ship_add1'] = $this->user->defaultShippingAddress->address->address_line;
        $post_data['ship_add2'] = "";
        $post_data['ship_city'] = $this->user->defaultShippingAddress->address->city;
        $post_data['ship_state'] = "";
        $post_data['ship_postcode'] = $this->user->defaultShippingAddress->address->postal_code;
        $post_data['ship_country'] = "Bangladesh";

        
        $product = $this->firstProduct();
        $post_data['product_name'] = $product->name; //"Computer";
        $post_data['product_category'] = "Goods";
        $post_data['product_profile'] = "general";
        // $post_data['product_amount'] = "";
        // $post_data['vat'] = "";
        // $post_data['discount_amount'] = "";
        // $post_data['convenience_fee'] = "";

        return $post_data;
    }

}
