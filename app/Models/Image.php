<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Image as ImageIntervention;

class Image extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function owner()
    {
        return $this->morphTo();
    }

    public function removeImagesFromStorage($path)
    {
        if (is_array($path)) {
            // foreach ($path as $image) {
            //     Storage::delete("public/{$image['path']}");
            // }
            foreach ($path as $image) {
                Storage::delete("public/{$image}");
            }
            return;
        }
        Storage::delete("public/{$path}");
        return $path;
    }

    public function saveImagesToStorage(string $directory, $imageFile, $name)
    {
        if ($directory === 'icons') {

            $imagePath = 'images/'."{$directory}/{$name}";            

            ImageIntervention::make($imageFile)->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save(storage_path().'/app/public/'.$imagePath, 80);

            return $imagePath;            
               
        } 
        
        if ($directory === 'slides') {
                $largeImgagePath = 'images/'."{$directory}/large/{$name}";            
                // $mediumImgagePath = 'images/'."{$directory}/medium/{$name}";

                $smallImgagePath = 'images/'."{$directory}/small/{$name}";

                // ImageIntervention::make($imageFile)->save(storage_path().'/app/public/'.$largeImgagePath, 80);
                ImageIntervention::make($imageFile)->resize(1976, 688, function ($constraint) {
                    // $constraint->aspectRatio();
                    // $constraint->upsize();
                })->save(storage_path().'/app/public/'.$largeImgagePath, 80);

                ImageIntervention::make($imageFile)->resize(200, 250, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(storage_path().'/app/public/'.$smallImgagePath, 60);

                
                // $path = $smallImgagePath;
                $path = [];
                $path[] = $largeImgagePath; 
                // $path[] = $mediumImgagePath; 
                $path[] = $smallImgagePath;

                return $path;
         
        }
           // else {
        $largeImgagePath = 'images/'."{$directory}/large/{$name}";            

        $mediumImgagePath = 'images/'."{$directory}/medium/{$name}";

        $smallImgagePath = 'images/'."{$directory}/small/{$name}";

        ImageIntervention::make($imageFile)->save(storage_path().'/app/public/'.$largeImgagePath, 80);

        ImageIntervention::make($imageFile)->resize(600, 800, function ($constraint) {
            $constraint->aspectRatio();
        })->save(storage_path().'/app/public/'.$mediumImgagePath, 80);

        ImageIntervention::make($imageFile)->resize(200, 250, function ($constraint) {
            $constraint->aspectRatio();
        })->save(storage_path().'/app/public/'.$smallImgagePath, 60);

        
        // $path = $smallImgagePath;
        $path = [];
        $path[] = $largeImgagePath; 
        $path[] = $mediumImgagePath; 
        $path[] = $smallImgagePath;

        return $path;
            
    }

}
