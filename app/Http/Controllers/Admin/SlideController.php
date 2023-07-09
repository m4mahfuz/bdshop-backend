<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SlideResource;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SlideController extends Controller
{
    protected $slide;

    public function __construct(Slide $slide)
    {
        $this->slide = $slide;
    }


    public function index()
    {
        return response([
            'data' => SlideResource::collection(Slide::all())           
        ], Response::HTTP_OK);
    }

    public function store()
    {        
        $attributes = $this->validateRequest();

        $image = $attributes['image'];

        unset($attributes['image']);

        
        $slide = DB::transaction(function() use($attributes, $image) {

            $slide = $this->slide->add($attributes);

            $this->slide->saveImageOf($image, $slide);
            
            return $slide;
        });        

        return response([
            'data' => SlideResource::make($slide)
        ], Response::HTTP_CREATED);

    }

    public function update(Slide $slide)
    {
        $attributes = $this->validateRequest();
        
        unset($attributes['image']);

        $slide = $slide->update($attributes);

        return response([
            'data' => $slide
        ], Response::HTTP_OK);
    }

    public function destroy(Slide $slide)
    {
        $largeImgagePath = "images/slides/large/{$slide->image->name}";            
        $smallImgagePath = "images/slides/small/{$slide->image->name}";            

        $slide->image->removeImagesFromStorage($largeImgagePath);
        $slide->image->removeImagesFromStorage($smallImgagePath);
        // From DB
        $slide->image->delete();
        $slide->delete();

        return response([], Response::HTTP_NO_CONTENT);
    }

    public function validateRequest()
    {
        return request()->validate([
            // 'images' => 'sometimes|required|array|min:1',
            'image' => 'sometimes|string',
            'discount' => 'nullable|int',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'url' => 'nullable|string',             
            'active' => 'required|boolean'
        ]);        
    }
}
