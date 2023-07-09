<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware([
            'auth:admin',
            'type:super-admin,admin'
        ])->except('index', 'show');
    }

    public function index()
    {
        return [ 
            'data' => TagResource::collection(
                Tag::all()
            )
        ];
    }

    public function store(StoreTagRequest $request)
    {

        return response([
            'data'=> new TagResource(
                Tag::create($request->validated())
            )
        ], Response::HTTP_CREATED);
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return response([], Response::HTTP_NO_CONTENT);
    }

    // protected function validateRequest(Tag $tag = null) {
    //     return request()->validate([
    //         'name' => [
    //             'required',
    //             'string',
    //             Rule::unique('tags', 'name')->ignore($tag?->id)
    //         ],
    //         'slug' => [
    //             'required',
    //             'string',
    //             Rule::unique('tags', 'slug')->ignore($tag?->id)
    //         ],
    //     ]);
    // }

}