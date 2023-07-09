<?php

namespace App\Http\Controllers;

use App\Models\Image as ImageModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Image;

class ImageController extends Controller
{
    protected $image;

    public function __construct(ImageModel $image)
    {
        $this->image = $image;
    }
   
    // public function store(Request $request)
    // {
    //     $attributes = $this->validateRequest();

    //     $file = $request->file('image');   
    //     $name = uniqid().'.'.$file->extension();

    //     if ($attributes['directory']) {

    //         $path = "{$attributes['directory']}/{$name}";
    //         // $request->file('image')->store("public/{$path}");
    //         $request->file('image')->storeAs(
    //             "public/{$attributes['directory']}", $name
    //         );
    //     } else {            
    //         $path = "images/{$name}";            
    //         // $request->file('image')->store("public/{$path}");
    //         $request->file('image')->storeAs(
    //             'public/images', $name
    //         );
    //     }

    //     // return [
    //     //     'message' => 'Image uploaded successfully',
    //     //     'name' => $name,
    //     //     'path' => $path,
    //     //     // 'title' => $attributes['title']
    //     // ];
    //     return response([
    //         'data' => [
    //             'message' => 'Image uploaded successfully',
    //             'name' => $name,
    //             // 'path' => $path,
    //             'path' => Storage::url($path),
    //         ] 
    //     ], Response::HTTP_CREATED);     
    // }
    public function store(Request $request)
    {
        $attributes = $this->validateRequest();

        $imageFile = $request->file('image');   
        $name = time().uniqid().'.'.$imageFile->extension();

        if ($attributes['directory']) {
            if ($attributes['directory'] === 'icons') {

                $imagePath = 'images/'."{$attributes['directory']}/{$name}";            
                Image::make($imageFile)->resize(100, 100, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save(storage_path().'/app/public/'.$imagePath, 80);
                $path = $imagePath;            
                
            } else {
                $largeImgagePath = 'images/'."{$attributes['directory']}/large/{$name}";            

                $mediumImgagePath = 'images/'."{$attributes['directory']}/medium/{$name}";

                $smallImgagePath = 'images/'."{$attributes['directory']}/small/{$name}";

                Image::make($imageFile)->save(storage_path().'/app/public/'.$largeImgagePath, 80);

                Image::make($imageFile)->resize(600, 800, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(storage_path().'/app/public/'.$mediumImgagePath, 80);

                Image::make($imageFile)->resize(200, 250, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(storage_path().'/app/public/'.$smallImgagePath, 60);

                
                // $path = $smallImgagePath;
                $path = [];
                $path[] = $largeImgagePath; 
                $path[] = $mediumImgagePath; 
                $path[] = $smallImgagePath;
                // $request->file('image')->storeAs(
                //     "public/{$attributes['directory']}", $name
                // );
            }                        
            
        } else {            
            $path = "images/{$name}";            
            // $request->file('image')->store("public/{$path}");
            $request->file('image')->storeAs(
                'public/images', $name
            );
        }

        return response([
            'data' => [
                'message' => 'Image uploaded successfully',
                'name' => $name,
                'path' => $path,
                // 'path' => Storage::url($path),
            ] 
        ], Response::HTTP_CREATED);     
    }

    public function destroy(Request $request)
    {
        $this->image->removeImagesFromStorage($request->path);

        
        return response([], Response::HTTP_NO_CONTENT);
    }

    protected function validateRequest()
    {
        return request()->validate([
           'image' => 'required|image|dimensions:min_width=300,min_height=250',
           // 'title' => 'required|min:5|max:150',
           'directory' => 'nullable|sometimes|required|string',
        ]);
    }        
}