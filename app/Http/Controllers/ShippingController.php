<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShippingResource;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShippingController extends Controller
{
    public function __invoke()
    {
        $shippingCities = Shipping::active()->get(['id', 'city']);
        return response([
            'data' => ShippingResource::collection($shippingCities)
        ], Response::HTTP_OK);
    }
}
