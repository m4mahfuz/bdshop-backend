<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use App\Services\AddressService;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\AddressResource;
use App\Http\Requests\StoreAddressRequest;
use Symfony\Component\HttpFoundation\Response;


class AddressController extends Controller
{
    private $address;

    public function __construct(AddressService $address)
    {
        $this->address = $address;
    }


    public function index()
    {
        return AddressResource::collection(
            Address::where('user_id', Auth::user()->id)->get()->sortByDesc('created_at')
        );
    }

    public function addresses()
    {
        return AddressResource::collection(
            Address::where('user_id', Auth::user()->id)->active()->get()->sortByDesc('created_at')
        );
    }

    public function shippingAddress()
    {
        return ['data' => Auth::user()->defaultShippingAddress->address];
    }

    public function store(StoreAddressRequest $request)
    {        
        return response([
            'data' => AddressResource::make($this->address->add($request))
        ], Response::HTTP_CREATED);
    }

    public function update(StoreAddressRequest $request, Address $address)
    {        
        return response([
            'data' => AddressResource::make($this->address->update($request, $address))
        ], Response::HTTP_OK);
    }

    public function destroy(Address $address)
    {
        $address->delete();

        return response([], Response::HTTP_NO_CONTENT);
    }   
}