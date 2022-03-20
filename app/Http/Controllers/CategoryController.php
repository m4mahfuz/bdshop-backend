<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
// use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
// use Illuminate\Http\Request;
// use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    private $category;

    public function __construct(CategoryService $category)
    {
        $this->category = $category;
        $this->middleware('auth')->except('index', 'show');
        $this->middleware('role:super_admin,admin')->except('index', 'show');
    }    

    public function index()
    {
      return [
            'data' => CategoryResource::collection(Category::all()),
        ];
    }
    
    public function store(StoreCategoryRequest $request)
    {
        return $this->category->add($request->validated());
    }
    
    public function show(Category $category)
    {
        return $this->category->show($category);
    }
    
    public function update(StoreCategoryRequest $request, Category $category)
    {
        return $this->category->update($category, $request->validated());
    }

    public function destroy(Category $category)
    {
        return $this->category->remove($category);
    }
}
