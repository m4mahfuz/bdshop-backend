<?php

namespace App\Http\Controllers;

use App\Models\Shipper;
use Illuminate\Http\Request;
use App\Http\Resources\ShipperResource;
use Symfony\Component\HttpFoundation\Response;

class ShipperController extends Controller
{
    public function index()
    {
        return response([
            'data' => ShipperResource::collection(Shipper::active()->get())
        ], Response::HTTP_OK);
    }
}
