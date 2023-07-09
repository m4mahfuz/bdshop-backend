<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use Illuminate\Http\Request;
use App\Http\Resources\SlideResource;
use Symfony\Component\HttpFoundation\Response;

class SlideController extends Controller
{
    public function index()
    {
        return response([
            'data' => SlideResource::collection(Slide::where('active', true)->get())           
        ], Response::HTTP_OK);
    }
}
