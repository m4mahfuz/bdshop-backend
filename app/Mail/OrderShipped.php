<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderShipped extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {        
        $this->order = $order;
        $this->onQueue('email');
    }


    

    public function envelope()
    {
        return new Envelope(
            subject: 'Order Shipped',
        );
    }

    
    public function content()
    {
        // dd($this->order);
        return new Content(
            view: 'emails.orders.shipped',
            with: [
                'coupon' => $this->order->coupon,
                'customer' => $this->order->user,
                'products' => $this->order->validProducts(),
                'shippingAddress' => $this->order->shippingAddress,                
                'shippingType' => $this->order->shippingType(),
                'trackingNo' => $this->order->shippingAddress->shipment->tracking_no,
                'shipperName' => $this->order->shippingAddress->shipment->shipper->name,
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
