<?php

namespace App\Http\Controllers;

use App\Http\Resources\DistrictResource;
use App\Models\District;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DistrictController extends Controller
{    
    public function __invoke()
    {
         return [
            'data' => DistrictResource::collection(District::all()),
        ];
    }
}