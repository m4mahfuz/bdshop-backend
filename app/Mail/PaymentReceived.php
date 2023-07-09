<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentReceived extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $payment;

    public function __construct(Payment $payment)
    {
        // $payment->load([
        //     'order'    
        // ]);

        $this->payment = $payment;        
        $this->onQueue('email');
    }    

    public function envelope()
    {
        return new Envelope(
            subject: 'Payment Received',
        );
    }
    
    public function content()
    {
        // dd($this->payment);
        return new Content(
            view: 'emails.payments.received',
            with: [
                'order' => $this->payment->order,
                'payment' => $this->payment,
                'coupon' => $this->payment->coupon,
                'customer' => $this->payment->order->user,
                'products' => $this->payment->order->validProducts(),
                'shippingAddress' => $this->payment->order->shippingAddress,                
                'shippingType' => $this->payment->order->shippingType(),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
