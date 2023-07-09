<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use App\Http\Resources\DeviceResource;
use Symfony\Component\HttpFoundation\Response;

class DeviceController extends Controller
{
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'token' => 'required|unique:devices'
        ]);

        $device = Device::create($attributes);

        return response([
            'data'=> DeviceResource::make($device)    
        ], Response::HTTP_CREATED);
    }

}
