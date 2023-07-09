<?php

namespace App\Http\Controllers;

use App\Http\Resources\InviteResource;
use App\Mail\InviteCreated;
use App\Models\Invite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class InviteController extends Controller
{
    public function index()
    {
        return response([
            'data' => InviteResource::collection(Invite::all())
        ], Response::HTTP_CREATED);
        
    }

    public function show($token)
    {
        $invite = Invite::where('token', $token)->first();

        return response([
            'data' => $invite
        ], Response::HTTP_OK);

    }

    public function process(Request $request)
    {
        // validate the incoming request data
        $request->validate([
            'email' => 'required|email',
            'type' => 'required|numeric|exists:types,id'
        ]);
 
        do {
            //generate a random string using Laravel's str_random helper
            $token = Str::random();
        } //check if the token already exists and if it does, try again
        while (Invite::where('token', $token)->first());
     
        //create a new invite record
        $invite = Invite::create([
            'email' => $request->input('email'),
            'type_id' => $request->input('type'),
            'token' => $token
        ]);
 
        // send the email
        Mail::to($request->input('email'))->send(new InviteCreated($invite));
     
        return response([
            'data' => InviteResource::make($invite)
        ], Response::HTTP_CREATED);
    }


    public function accept($token)
    {
        // Look up the invite
        if (!$invite = Invite::where('token', $token)->first()) {
            //if the invite doesn't exist do something more graceful than this
            abort(404);
        }
     
        // create the user with the details from the invite
        // User::create(['email' => $invite->email]);
     
        // delete the invite so it can't be used again
        // $invite->delete();
     
        // here you would probably log the user in and show them the dashboard, but we'll just prove it worked
     
        // return 'Good job! Invite accepted!';
        $clientUrl = config('app.client_url');
        $clientUrl = "{$clientUrl}/admin/register/{$token}";
        // return $clientUrl;
        return Redirect::away($clientUrl);
    }

    public function destroy(Invite $invite)
    {
        $invite->delete();

        return response([], Response::HTTP_NO_CONTENT);

    }

}
