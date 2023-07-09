<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\OrderProductDisputeService;

class OrderProductDisputeController extends Controller
{
    private $orderProductDispute;

    public function __construct(OrderProductDisputeService $orderProductDispute)
    {
        $this->orderProductDispute = $orderProductDispute;
    }


    public function cancel(Order $order, Request $request)
    {
        //
        $validated = $request->validate([
            'reason' => 'required|string',
            // 'payment_details' => 'nullable|sometimes|array'
        ]);

        return $this->orderProductDispute->cancel($order, $request);

    }

    public function cancelItems(Order $order, Request $request)
    {
        $validated = $request->validate([
            'order' => 'required|numeric|exists:orders,id',
            'items' => 'required|array', 
            // 'payment_details' => 'nullable|sometimes|array'
        ]);

        if ( count($validated['items']) === count($order->products) ) {
            return $this->orderProductDispute->cancel($order, $request);

        } 

        // OrderProduct::entry($validated['items'], 'Cancelled');

        return $this->orderProductDispute->cancelItems($order, $validated['items'], $request);

        // if $order COD then 
        //net_total of order to be adjusted
        //  amount of payment will be same as order

        // else, for prepaid initiate payment return for returned products  


        // if ($order->payment_method === Payment::PAYMENT_METHOD_PREPAID) {
        //     Payment::verify();
        //     Payment::update()
        // }
        // return ['msg' => 'success'];     

    }
}
