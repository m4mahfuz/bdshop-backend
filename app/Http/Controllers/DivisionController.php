<?php

namespace App\Http\Controllers;

use App\Http\Resources\DivisionResource;
use App\Models\Division;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DivisionController extends Controller
{    
    public function __invoke()
    {
         return [
            'data' => DivisionResource::collection(Division::all()),
        ];
    }
}