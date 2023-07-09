<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Type;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TypeController extends Controller
{

    public function __construct()
    {
        $this->middleware([
            'auth:sanctum',
            'type:super-admin'
        ]);//->except('index', 'show');
    }


    public function index()
    {
        $types = Type::where('name', '!=', 'super-admin')->get(['id', 'name']);

        return response([
            'data' => $types           
        ], Response::HTTP_OK);
        
    }
}