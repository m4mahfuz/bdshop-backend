<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\ShippingTypeResource;
use Illuminate\Http\Request;
use App\Models\Shipping;

class ShippingTypeController extends Controller
{
    public function shippingTypesByCity(Request $request)
    {
        $shipping = Shipping::byCity($request->input('city'));
        // $shippingTypes = $shipping->shippingTypes('active');
        $shippingTypes = $shipping->shippingTypes()->active()->get();

        // 'shipping_types' => ShippingTypeResource::collection($shippingTypes),

        return response([
            'data' => ShippingTypeResource::collection($shippingTypes->load(['shippingCharge' => function($query) {
                    $query->where('active', true);
            }]))
        ], Response::HTTP_OK);
        
    }
}
