<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShippingRequest;
use App\Http\Resources\ShippingTypeResource;
use App\Models\ShippingType;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShippingTypeController extends Controller
{
    
    public function update(StoreShippingRequest $request, ShippingType $shipping)
    {
        $shipping->update([             
            'active' => $request->getShippingTypesActive(),
        ]);

        return response([
            'data' => ShippingTypeResource::make($shipping)
        ], Response::HTTP_OK);
    }
    
}
