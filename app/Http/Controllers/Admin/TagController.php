<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Resources\TagCollection;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use App\Services\TagService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TagController extends Controller
{
    private $tag;

    public function __construct(TagService $tag)
    {
        $this->tag = $tag;
    }
    
    public function index()
    {        
        $tags = Tag::with([
            'category:id,name,slug,parent_id',
        ])->orderBy('id', 'desc')->cursorPaginate(10);

        return (new TagCollection($tags))->additional(
            [
                'meta' => [
                    'totalTags' => Tag::count(), //
                ]
            ]
        );
    }

   public function store(StoreTagRequest $request)
    {

        return response([
            'data'=> new TagResource(
                $this->tag->add($request)
            )
        ], Response::HTTP_CREATED);
    }

    public function update(StoreTagRequest $request, Tag $tag)
    {        
        return response([
            'data' => TagResource::make($this->tag->update($request, $tag))
        ], Response::HTTP_OK);
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return response([], Response::HTTP_NO_CONTENT);
    }

    public function tagsByCategory($id)
    {
        return [ 
            'data' => TagResource::collection(
                Tag::getTagsByCategory($id)
            )
        ];
    }
    
}