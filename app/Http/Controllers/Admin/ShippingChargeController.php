<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShippingRequest;
use App\Http\Resources\ShippingChargeResource;
use App\Models\ShippingCharge;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShippingChargeController extends Controller
{
    
    public function update(StoreShippingRequest $request, ShippingCharge $shipping)
    {
        $shipping->update([             
            'active' => $request->getShippingChargeActive(),
        ]);

        return response([
            'data' => ShippingChargeResource::make($shipping)
        ], Response::HTTP_OK);
    }    
}
