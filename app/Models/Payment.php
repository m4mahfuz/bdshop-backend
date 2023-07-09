<?php

namespace App\Models;

use App\Models\Order;
use App\Mail\PaymentReceived;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;
    protected $guarded = [];

    const PAYMENT_METHOD_COD = 1;
    const PAYMENT_METHOD_PREPAID = 2;

    public function detail()
    {
        return $this->hasOne(PaymentDetail::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public static function for(Order $order)
    {
         return self::updateOrCreate(
            ['order_id' => $order->id],
            [
                'transaction_id' => $order->uuid,
                'amount' => $order->net_total, 
                'method' => $order->payment_method,
                'status' => 'Pending',//$order->status
            ]   
        );   
    }

    public function mailToUser()
    {
        return Mail::to($this->order->user)->send(new PaymentReceived($this));
    }

    public static function redirectToClientOn($value, $orderId)
    {
        $config = config('payment.client');
        $url = $config['url'];
        if ($value === 'success') {
         // return  ('http://localhost:80/orders/success');   
         return  ("${url}/orders/${orderId}/payments/success");   
        }
        // if ($value === 'cancel') {
        //  // return  ('http://localhost:80/orders/cancel');   
        //  return  ("${url}/orders/${orderId}/payments/cancelled");   
        // }

        return  ("${url}/orders/${orderId}/payments/failed");   
    }

}
