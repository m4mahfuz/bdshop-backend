<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryService 
{

	public function add(arry $attributes)
	{
		return response([
            'data'=> new CategoryResource(
                Category::create($attributes)
            )
        ], Response::HTTP_CREATED);
	}

	public function show(Category $category)
	{
		return new CategoryResource($category);
	}

	public function update(Category $category, array $attributes)
	{
		$category->fill($attributes);
		$category->save();

		return response([
			'data' => new CategoryResource($category->fresh())
		], Response::HTTP_OK );
	}

	public function remove(Category $category)
	{
	  $category->delete();
	   return response([], Response::HTTP_NO_CONTENT);
	}

}