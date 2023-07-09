<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Http\Resources\WishlistResource;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class WishlistController extends Controller
{
    public function index()
    {
     
        // Auth::loginUsingId(1);
        $wishlist = Wishlist::where('user_id', Auth::user()->id)->with([
            'product',
        ])->orderBy('id')->paginate(3);

        return WishlistResource::collection($wishlist);
     
    }

    public function destroy(Wishlist $wishlist)
    {        
        $wishlist?->delete();
        return response([], Response::HTTP_NO_CONTENT);
    }
}
