<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use \Illuminate\Mail\Mailables\Attachment;

class OrderDelivered extends Mailable implements ShouldQueue
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
            subject: 'Order Delivered',
        );
    }
    
    public function content()
    {
        // dd($this->order);
        return new Content(
            view: 'emails.orders.delivered',
            with: [
                'coupon' => $this->order->coupon,
                'customer' => $this->order->user,
                'products' => $this->order->validProducts(),
                'shippingAddress' => $this->order->shippingAddress,                
                'shippingType' => $this->order->shippingType(),
            ],
        );
    }
    
    public function attachments()
    {
        $fileName = "invoice_{$this->order->uuid}.pdf";
        $path = public_path('storage/pdf/invoices/');

        return [
            Attachment::fromPath("{$path}/{$fileName}")
                    ->as("{$fileName}")
                    ->withMime('application/pdf'),
        ];
    }
}
