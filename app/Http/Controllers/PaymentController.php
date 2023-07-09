<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PaymentController extends Controller
{
    protected $payment;

    public function __construct(PaymentService $payment)
    {
        $this->payment = $payment;
    }

    public function store(Request $request)
    {
       return $this->payment->add($request);

    }

    public function success(Request $request)
    {
        $response = $this->payment->handleSuccess($request);

        if ($response['status'] === true) {
            //empty cart
            //Cart::empty();
            $clientUrl = Payment::redirectToClientOn('success', $response['order_id']);
            // return Redirect::away('http://localhost:80/orders/success');
            return Redirect::away($clientUrl);
        }

        $clientUrl = Payment::redirectToClientOn('error', $response['order_id']);
        
        return Redirect::away($clientUrl);

        // return ['error' => $response['msg']];

    }
}