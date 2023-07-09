<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use App\Models\Category;

class CategoryController extends Controller
{
    private $category;

    public function __construct(CategoryService $category)
    {
        $this->middleware([
            'auth:admin',
            'type:super-admin,admin'
        ])->except('index', 'show');

        $this->category = $category;
    }    

    public function index()
    {        
        return response([
            'data' => CategoryResource::collection(Category::root()->with([
                'children', 
                'meta:id,title,description,keywords,owner_id',
            ])->latest()->get()),
        ], Response::HTTP_OK);
    }
    
    public function store(StoreCategoryRequest $request)
    {
        return response([
            'data'=> CategoryResource::make(
                $this->category->add($request)
            )
        ], Response::HTTP_CREATED);
    }
    
    public function show(Category $category)
    {
        // $category->load([
        //     'products.featuredImage',
        //     'products.discount',            
        // ]);
        return CategoryResource::make($category->load('meta:id,title,description,keywords,owner_id'));
    }
    
    public function update(StoreCategoryRequest $request, Category $category)
    {
        // $category->fill($request->validated());
        // $category->save();

        // return response([
        //     'data' => CategoryResource::make($category->fresh())
        // ], Response::HTTP_OK );
        return response([
            'data' => CategoryResource::make($this->category->update($request, $category))
        ], Response::HTTP_OK );
    }

    public function destroy(Category $category)
    {
        if ($category->image !== null) {            
            $this->destroyImage($category);
        }

        $category->delete();
        return response([], Response::HTTP_NO_CONTENT);
    }

    // public function destroyIcon(Category $category, $name)
    public function destroyIcon(Category $category)
    {
        // $path = "images/icons/{$name}";
        $path = "images/icons/{$category->image->name}";
        return $category->image->removeImagesFromStorage($path);

        $category->removeFromDB('icon');
        
        return response([], Response::HTTP_NO_CONTENT);
    }

    // public function destroyImage(Category $category, $name)
    public function destroyImage(Category $category)
    {
        $name = $category->image->name;

        $largeImgagePath = "images/categories/large/{$name}";            
        $mediumImgagePath = "images/categories/medium/{$name}";
        $smallImgagePath = "images/categories/small/{$name}"; 

        $category->image->removeImagesFromStorage($largeImgagePath);
        $category->image->removeImagesFromStorage($mediumImgagePath);
        $category->image->removeImagesFromStorage($smallImgagePath); 

        $category->removeFromDB('image');
        // $category->image->delete();
        return response([], Response::HTTP_NO_CONTENT);
    }
}
