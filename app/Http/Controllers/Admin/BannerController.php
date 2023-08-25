<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class BannerController extends Controller
{
    protected $banner;

    public function __construct(Banner $banner)
    {
        $this->banner = $banner;
    }


    public function index()
    {
        return response([
            'data' => BannerResource::collection(Banner::all())           
        ], Response::HTTP_OK);
    }

    public function store()
    {        
        $attributes = $this->validateRequest();

        $image = $attributes['image'];

        unset($attributes['image']);

        
        $banner = DB::transaction(function() use($attributes, $image) {

            $banner = $this->banner->add($attributes);

            $this->banner->saveImageOf($image, $banner);
            
            return $banner;
        });        

        return response([
            'data' => BannerResource::make($banner)
        ], Response::HTTP_CREATED);

    }

    public function update(Banner $banner)
    {
        $attributes = $this->validateRequest();
        
        unset($attributes['image']);

        $banner = $banner->update($attributes);

        return response([
            'data' => $banner
        ], Response::HTTP_OK);
    }

    public function destroy(Banner $banner)
    {
        $largeImgagePath = "images/banners/large/{$banner->image->name}";            
        $mediumImgagePath = "images/banners/medium/{$banner->image->name}";            
        $smallImgagePath = "images/banners/small/{$banner->image->name}";            

        $banner->image->removeImagesFromStorage($largeImgagePath);
        $banner->image->removeImagesFromStorage($mediumImgagePath);
        $banner->image->removeImagesFromStorage($smallImgagePath);
        // From DB
        $banner->image->delete();
        $banner->delete();

        return response([], Response::HTTP_NO_CONTENT);
    }

    public function validateRequest()
    {
        return request()->validate([
            // 'images' => 'sometimes|required|array|min:1',
            'image' => 'sometimes|string',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'url' => 'nullable|string',             
            'active' => 'required|boolean'
        ]);        
    }
}
