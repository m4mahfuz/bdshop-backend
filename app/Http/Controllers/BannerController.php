<?php

namespace App\Http\Controllers;

use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BannerController extends Controller
{
    public function index()
    {
        return response([
            'data' => BannerResource::collection(Banner::where('active', true)->limit(2)->get())           
        ], Response::HTTP_OK);
    }
}
