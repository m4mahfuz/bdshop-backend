<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Services\PaymentService;
use App\Services\ProductService;
use Illuminate\Support\Facades\DB;
use App\Events\OrderStatusUpdated;
use App\Models\OrderProductDispute;

class OrderProductDisputeService 
{
    protected $payment;
    protected $product;

    public function __construct(PaymentService $payment, ProductService $product)
    {
        $this->payment = $payment;
        $this->product = $product;
    }

    public function cancel(Order $order, Request $request)
    {
        return DB::transaction(function() use ($order, $request){

        
            $items = [];

            foreach ($order->products as $product) {
                $items[] = [
                    'id' => $product->pivot->id,
                    'reason' => $request->input('reason')
                ]; 
            }

            OrderProductDispute::entry($items, 'Cancelled');

            if ($order->payment_method === Payment::PAYMENT_METHOD_COD) {
                $order->payment()->update(['status' => 'Cancelled']);
            } 

            if ($order->payment_method === Payment::PAYMENT_METHOD_PREPAID) {
                
                // $order->payment()->returnRequest();          
                $this->payment->refundRequest($order->payment, $order->net_total);
            } 
            
            // update order status
            $order->update(['status' => 'Cancelled']);
            
            //create logs
            OrderStatusUpdated::dispatch($order);

            return ['message' => 'Cancelled successfully'];
        });
        
    }

    public function cancelItems(Order $order, array $items, Request $request)
    {
        $product = $this->product;

        return DB::transaction(function() use ($order, $product, $items, $request) {            

            OrderProductDispute::entry($items, 'Cancelled');

            // $amountOfDisputedItems = OrderProductDispute::amountOf($order, $product, $items);
            $amount = OrderProductDispute::amountOf($order, $product, $items);
            
            $amountOfDisputedItems = $amount['amount'];

            $couponAmountOfDisputedItems = round($amount['couponDiscountAmount']);


            // if ($order->payment_method === Payment::PAYMENT_METHOD_COD) {

            //     // $order->payment()->update(['status' => 'Cancelled']);
            //     // update payment amount

            // } 
            // update order total & net_total
            $order->update([
                'total' => $order->total - $amountOfDisputedItems,
                'net_total' => $order->net_total - ($amountOfDisputedItems + $couponAmountOfDisputedItems),
            ]);

            $order->coupon()->update([
                'amount' => $order->coupon->amount - $couponAmountOfDisputedItems
            ]);
            
            // update payment amount 
            // if ($order->payment_method === Payment::PAYMENT_METHOD_COD) {
                $order->payment()->update([
                    'amount' => $order->net_total
                ]);
            // }

            if ($order->payment_method === Payment::PAYMENT_METHOD_PREPAID) {
                
                // $order->payment()->returnRequest();          
                $this->payment->refundRequest($order->payment, $amountOfDisputedItems);            
            }

            // $order->update(['status' => 'Cancelled Partially']);
          
            //create logs
            OrderStatusUpdated::dispatch($order);

            return ['message' => 'Cancelled successfully'];
        });
        
    }
	
}