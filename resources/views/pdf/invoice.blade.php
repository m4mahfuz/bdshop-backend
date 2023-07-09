<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Bengal Shop Invoice</title>

    <style>
      .invoice-box {
        max-width: 800px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        font-size: 16px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
      }

      .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
      }

      .invoice-box table td {
        padding: 5px;
        vertical-align: top;
      }

      /*.invoice-box table tr td:nth-child(2) {
        text-align: right;
      }*/
      
      .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
      }

      .invoice-box table tr.information table td {
        padding-bottom: 20px;
      }

      .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
      }

      .invoice-box table tr.details td {
        padding-bottom: 20px;
      }

      .invoice-box table tr.item td {
        border-bottom: 1px solid #eee;
      }

      .invoice-box table tr.item.last td {
        border-bottom: none;
      }

      .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
      }

      .text-xl {
        font-size: 1.25rem;
        line-height: 1.75rem;
      }
      .font-bold {
        font-weight: 700;
      }
      .text-gray-400 {
        color: rgb(156, 163, 175);
      }
      .text-gray-800 {
        color: rgb(31, 41, 45);
      }

      @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
          width: 100%;
          display: block;
          text-align: center;
        }

        .invoice-box table tr.information table td {
          width: 100%;
          display: block;
          text-align: center;
        }
      }

      /** RTL **/
      .invoice-box.rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
      }

      .invoice-box.rtl table {
        text-align: right;
      }

      .invoice-box.rtl table tr td:nth-child(2) {
        text-align: left;
      }
    </style>
  </head>

  <body>
    <div class="invoice-box">
      <table cellpadding="0" cellspacing="0">
        <tr class="top">
          <td colspan="4">
            <table>
              <tr>
                <td class="text-xl text-gray-800 font-bold">
                  Invoice # {{$order->id}}<br />
                  @php
                   echo DNS1D::getBarcodeHTML($order->id, 'C39'); 
                  @endphp
                </td>

                <td style="text-align: right;">
                  <img src="{{url('/storage/images/logo.png')}}" style="max-width: 80px" />
                  <span style="display: block; font-size: 1.10rem; font-weight: 600; padding-top: 0.25rem" class="text-gray-800">
                      Bengal Shop
                  </span>
                  <span style="display: block;color: rgb(75, 85, 99); font-weight: 300; font-size: 1rem; padding-top:0.35rem;" >
                      Dhanmondi, Dhaka
                  </span>
                  <p class="text-gray-400" style="font-weight: 300; font-size: 1rem">{{Carbon\Carbon::now()->format('F j, Y')}}</p>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <tr class="information">
          <td colspan="4">
            <table>
              <tr>
                <td>
                  <h2 style="text-transform: uppercase;">Bill & Ship</h2>                             
                  <address>
                    {{$order->shippingAddress->name}}<br>
                    {{$order->shippingAddress->address_line}}<br>
                    {{$order->shippingAddress->city}}<br>
                    {{$order->shippingAddress->phone}}<br>
                </address>   
                </td>

                <td style="text-align: right; vertical-align: bottom;">
                  <h3 style="text-transform: uppercase;">Payment Method: {{$order->paymentMethod()}}
                  </h3>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <tr class="heading">
          <td>#</td>
          <td>Item</td>
          <td>Qty</td>
          <td>Total</td>
        </tr>
        @php
            $index = 0;
        @endphp

        @foreach($products as $product)
          <tr class="item">
            <td vertical-align="top">{{ $index = $index+1 }}</td>
            <td>
              <div style="font-size: 0.9rem; font-weight: 600;">{{$product->pivot->name}}</div>
                <span style="font-weight:300;">
                  {{$product->unit_quantity}} {{$product->unit}}
                </span>
                @if (is_null($product->pivot->discounted_price)) 
                <span style="display:block; padding-top: 0.25rem; font-size: 0.9rem;">Tk {{ (int) $product->pivot->price }} </span>
                @else 
                <span style="display:block; padding-top: 0.25rem; font-size: 0.9rem;"> Tk {{ (int) $product->pivot->discounted_price }} </span>
                @endif
            </td>
            <td>{{$product->pivot->quantity}}</td>
            <td>Tk {{ (int)$product->totalPrice() }}</td>
          </tr>

          
          </tr>
        @endforeach  

        <tr>                               
            <td colspan="3" align="right" style="text-transform: uppercase; padding-right: 1.5rem; font-weight: 600;"><span>Subtotal</span></td>
            
            <td>Tk {{ (int) $order->total}}</td>
        <tr>
            <td colspan="3" align="right" style="text-transform: uppercase; padding-right: 1.5rem;"><span>Shipping Charge</span></td>
            
            <td> Tk {{ (int) $order->shipping_charge}}</td>
        </tr>               
        <tr>
            <td colspan="3" align="right" style="text-transform: uppercase; padding-right: 1.5rem;"><span>Coupon discount (-)</span></td>

            <td> Tk {{ (int) $order->coupon?->amount}}</td>
        </tr>               
        <tr>
            <td colspan="3" align="right" style="text-transform: uppercase; padding-right: 1.5rem; font-weight: 700; font-size: 1rem;"><span>Net Total</span></td>
            <td style="font-weight: 700; font-size: 1rem;">Tk {{ (int) $order->net_total}}</td>
        </tr>               
        <tr><td colspan="4" style="padding: 10px 0;"></td></tr>

        <tr>
          <td colspan="2" style="border-top: 1px solid #eee;">
             <p>*This invoice is generated electronically.</p>
          </td>
          <td style="border-top: 1px solid #eee;"></td>
          <td style="border-top: 1px solid #eee; vertical-align: middle;">
              @php
                echo DNS2D::getBarcodeHTML("http://localhost/orders/{$order->uuid}/invoice", 'QRCODE',3,3);
              @endphp
          </td>
        </tr>      

      </table>
    </div>
  </body>
</html>