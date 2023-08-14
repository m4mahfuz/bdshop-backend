<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShippingRequest;
use App\Http\Resources\ShippingCollection;
use App\Http\Resources\ShippingResource;
use App\Models\Shipping;
use App\Services\ShippingService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShippingController extends Controller
{
    private $shipping;

    public function __construct(ShippingService $shipping)
    {
        $this->shipping = $shipping;
    }

    public function index()
    {        
        $shippings = Shipping::with([            
            'shippingTypes:id,active,type,delivery_time_min,delivery_time_max,shipping_id',
            'shippingTypes.shippingCharge:id,active,charge,shipping_type_id',
        ])->orderBy('id', 'desc')->paginate(10);

        return (new ShippingCollection($shippings))->additional(
            [
                'meta' => [
                    'totalCities' => Shipping::count(), //
                ]
            ]
        );

    }    

    public function show(Shipping $shipping)
    {   
        return response(['data' => ShippingResource::make($shipping)], Response::HTTP_OK);
    }

    public function store(StoreShippingRequest $request)
    {
        return response([
            'data'=> ShippingResource::make(
                $this->shipping->add($request)
            )
        ], Response::HTTP_CREATED);
    }

    public function update(Shipping $shipping, StoreShippingRequest $request)
    {        
        return response([
            'data' => ShippingResource::make($this->shipping->update($request, $shipping))
        ], Response::HTTP_OK);
    }    

    public function destroy(Shipping $shipping)
    {
        $shipping->delete();
        return response([], Response::HTTP_NO_CONTENT);
    }
}
