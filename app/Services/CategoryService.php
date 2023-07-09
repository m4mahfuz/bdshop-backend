<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StoreCategoryRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class CategoryService 
{
	public function add(StoreCategoryRequest $request)
	{
		
		$category = DB::transaction(function() use($request) {

			$category = Category::create([
				'name' => $request->getName(),
                'slug' => $request->getSlug(),
                'description' => $request->getDescription(),
                'parent_id' => $request->getParentId(),
                'active' => $request->getActive(),
                'icon' => $request->getIcon(),
			]);

			$category->image()->create([
	            'name' => $request->getImage()
	        ]);

			$meta = $category->meta()->create([
                'title' => $request->getMetaTitle(),
                'description' => $request->getMetaDescription(),
                'keywords' => $request->getMetaKeywords(),
			]);

			return $category;
		});
		
		return $category->load('meta:id,title,description,keywords,owner_id');
	}

	public function update(StoreCategoryRequest $request, Category $category)
	{		
		$category = DB::transaction(function() use($request, $category) {
			
			$category->update([
				'name' => $request->getName(),
                'slug' => $request->getSlug(),
                'description' => $request->getDescription(),
                'parent_id' => $request->getParentId(),
                'active' => $request->getActive(),
                'icon' => $request->getIcon(),
			]);			

			$category->image()->updateOrCreate([
	            'name' => $request->getImage()
	        ]);

			$this->updateChildrenActiveStatusAsOfParrent($category, $request->getActive());
						
			$meta = $category->meta()->update([
                'title' => $request->getMetaTitle(),
                'description' => $request->getMetaDescription(),
                'keywords' => $request->getMetaKeywords(),
			]);

			return $category;
		});
		

		return $category->load('meta:id,title,description,keywords,owner_id');
	}

	public function updateChildrenActiveStatusAsOfParrent(Category $category, $status)
	{
		if ($category->children()->count() > 0) {
			$category->children()->update(['active' => $status]);
		}
	}
	
}