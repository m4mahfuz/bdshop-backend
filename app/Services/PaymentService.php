<?php

namespace App\Services;

use App\Events\OrderStatusUpdated;
use App\Events\PaymentStatusUpdated;
use App\Models\Order;
use App\Models\Payment;
use App\Repositories\Payment\PaymentInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PaymentService 
{
	protected $payment;

	public function __construct(PaymentInterface $payment)
	{
		$this->payment = $payment;
	}
	
	public function add(Request $request)
	{        
		$data = [];
        $order = Order::find($request->input('order'));

		if ($order === null || $order->payment_method !== Payment::PAYMENT_METHOD_PREPAID) {
			return;
		} 
		
		// get booking information		
		$data = $order->getData(); 

		// $data['status'] = 'Pending';
        $data['ipn_url'] = url('/') . config('payment.sslcommerz.ipn_url');

        // $order->savePayment(); 
        Payment::for($order);

        // return $payment_options = $this->payment->initiateGWPayment($data);
        $responseFromGW = $this->payment->initiateGWPayment($data);

        # PARSE THE JSON RESPONSE
        $response = json_decode($responseFromGW, true);        

        return $this->payment->formatResponse($response);

        // if (!is_array($payment_options)) {
        //     print_r($payment_options);
        //     $payment_options = array();
        // }
	}

	public function refundRequest(Payment $payment, $amount)
	{
		$data = [];
        $data = [
            'bank_tran_id' => $payment->detail->bank_transaction_id,
            'refund_amount' => $amount,
            'refund_remarks' => 'Testing refund',
            'refe_id'  => $payment->id,

        ];
		$responseFromGW = $this->payment->initiateRefund($data);
		# PARSE THE JSON RESPONSE
        $response = json_decode($responseFromGW, true);

        if ($response['APIConnect'] === 'Done' && $response['status']) {
        	$payment->order()->update([
        		'status' => 'Refund Processing'
        	]);
        }
        
        $payment->paymentReturn()->firstOrCreate([
        	'amount' => data['refund_amount'],
        	'APIConnect' => $response['APIConnect'],
        	'bank_transaction_id' => $response['bank_tran_id'],
        	'trans_id' => $response['trans_id'],
        	'refund_ref_id' => $response['refund_ref_id'],
        	'status' => $response['status'],
        	'error_reason' => $response['errorReason'],
        ]);
	}

	public function refundStatusChecking($value='')
	{
		// code...
	}

	public function savePaymentDetails(Order $order, $response) {
		return $order->payment->detail()->firstOrCreate([
			'store_amount' => $response->store_amount, 
			'bank_transaction_id' => $response->bank_tran_id,
			'transaction_date' => $response->tran_date,
			'payment_gateway' => $response->card_type	
		]);
	}

	public function handleSuccess(Request $request)
	{

        //echo "Transaction is Successful";
        $msg = "Transaction is Successful";

        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');

        
        #Check order status in order tabel against the transaction id or order id.
        /*$order_detials = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount')->first();*/

        // $order_detials = $this->payment->detailsBy($tran_id);
        $order = Order::detailsBy($tran_id);

        if ($order->payment->status === 'Pending') {

                $validation = $this->payment->orderValidate($tran_id, $amount, $currency, $request->all());

                if ($validation['status'] === TRUE) {
                    /*
                    That means IPN did not work or IPN URL was not set in your merchant panel. Here you need to update order status
                in order table as Processing or Complete.
                Here you can also sent sms or email for successfull transaction to customer
                */
                /*$update_product = DB::table('orders')
                    ->where('transaction_id', $tran_id)
                    ->update(['status' => 'Processing']);
                echo "<br >Transaction is successfully Completed";*/
                $this->updateStatusTo('Processing', $order, 'order');

                $this->updateStatusTo('Complete', $order, 'payment',);

                $this->savePaymentDetails($order, $validation['response']);

                // send sms

                // $order->payment->updateStatusTo('Complete');


                // $update_payment = $this->payment->updatePayment('Complete', $tran_id);

                // $this->callToUpdateSeatStatusBy($order->booking_id);

                //echo "Transaction is successfully Completed";
                $msg = "Transaction is successfully Completed";
                
                // $options = $this->paymentStatusOptions('success', $msg, 'Thank You!');

                // return view('payment.status', $options);
                return [
                	'status' => true,
                	'msg'	=> $msg,
                    'order_id' => $order->id
                ];

            } else {
                /*
                That means IPN did not work or IPN URL was not set in your merchant panel and Transation validation failed.
                Here you need to update order status as Failed in order table.
                */
                /*$update_product = DB::table('orders')
                    ->where('transaction_id', $tran_id)
                    ->update(['status' => 'Failed']);
                
                echo "validation Fail";*/

                // $update_product = $this->payment->updatePayment('Failed', $tran_id);

                $msg = "Validation Fail";
                
                // $options = $this->paymentStatusOptions('error', $msg, 'Oops!');
                // $options['booking_id'] = $order->booking_id;

                // return view('payment.status', $options);
                // return false;
                return [
                	'status' => false,
                	'msg'	=> $msg,
                    'order_id' => $order->id
                ];

            }
        } else if ($order->payment->status == 'Processing' || $order->payment->status == 'Complete') {
            /*
             That means through IPN Order status already updated. Now you can just show the customer that transaction is completed. No need to udate database.
             */
            //echo "Transaction is successfully Completed";
            $msg = "Transaction is successfully Completed";
            
            // $options = $this->paymentStatusOptions('success', $msg, 'Thank You!');

            // return view('payment.status', $options);
            // return true;
            return [
            	'status' => true,
            	'msg'	=> $msg,
                'order_id' => $order->id
            ];

        } else {
            #That means something wrong happened. You can redirect customer to your product page.
            //echo "Invalid Transaction";
            $msg = "Invalid Transaction";

            // $options = $this->paymentStatusOptions('error', $msg, 'Oops!');                
            // $options['booking_id'] = $order->booking_id;

            // return view('payment.status', $options);
            // return false;
            return [
            	'status' => false,
            	'msg'	=> $msg,
                'order_id' => $order->id
            ];
        }
	}

	public function updateStatusTo($status, Order $order, $type='order')
    {
    	// $time = now();
    	if ($type === 'order') {
    		$order->update(['status' => $status]);
    		// event('orderStatusUpdated', $status, $time, $order,);
    		OrderStatusUpdated::dispatch($order);
    		return;
    	}

    	$order->payment->update(['status' => $status]);
    	//sms to admin payment made
		// event('orderPaymentStatusUpdated', $status, $time, $order,);   	

    	// PaymentStatusUpdated::dispatch($order->payment, $status, now());
        PaymentStatusUpdated::dispatch($order->payment);
        return; 
    }

	public function update(StoreCartRequest $request, Cart $cart)
	{
		// $product->fill([
		// 	'name' => $request->getName(),
  //           'description' => $request->getDescription(),
  //           'sku' => $request->getSku(),
  //           'category_id' => $request->getCategoryId(),
		// ]);

		// $product->save();
		// $product->fresh();
		// $product->load('category');
		// $product->load('inventory.discount');

		// return $product;
	}

	// public function generateSessionIdIfNotExists()
	// {
	// 	$session_id = Session::get('session_id');

	// 	if (empty($session_id)) {
	// 		$session_id = Session::getId();
	// 		Session::put('session_id', $session_id);
	// 	}
	// 	return $session_id;
	// }

	
}