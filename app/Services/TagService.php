<?php

namespace App\Services;

use App\Http\Requests\StoreTagRequest;
use App\Models\Tag;
use Symfony\Component\HttpFoundation\Response;

class TagService 
{

	public function add(StoreTagRequest $request)
	{
		$tag = Tag::create([
		    'name' => $request->getName(),
		    'slug' => $request->getSlug(),
		    'category_id' => $request->getCategoryId(),	
			'active' => $request->getActive(),
		]);
		
		return $tag;
	}

	public function update(StoreTagRequest $request, Tag $tag)
	{		
		$tag->update([
			'name' => $request->getName(),
		    'slug' => $request->getSlug(),
		    'category_id' => $request->getCategoryId(),	
			'active' => $request->getActive(),
		]);
		
		return $tag;
	}	
	
}