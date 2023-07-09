<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\OrderStatusResource;
use App\Models\OrderStatus;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware([
    //         'auth:admin',
    //         'type:super-admin,admin'
    //     ]);//->except('index', 'show');

    // }

    public function index()
    {
         // code...
        return response([
            'data' => OrderStatusResource::collection(OrderStatus::active()->get())
        ], Response::HTTP_OK);
    }
}
