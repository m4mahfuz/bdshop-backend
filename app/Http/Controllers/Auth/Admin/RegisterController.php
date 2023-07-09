<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminUser;
use App\Models\Invite;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    public function __invoke()
    {
        $user = DB::transaction(function() {


            request()->validate([
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'unique:admins'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:admins', 'exists:invites,email'],
                'password' => ['required', 'confirmed'],
                'type_id' => ['required'],
                'token' => ['required', 'exists:invites,token'],
            ]);

            $user = Admin::create([
                'name' => request('name'),
                'phone' => request('phone'),
                'email' => request('email'),
                'password' => Hash::make(request('password')),
                'type_id' => request('type_id')
            ]);


            $email = request()->input('email');
            $token = request()->input('token');

            // Retrieve the invite information from the database based on the email and code
            $invite = Invite::where('email', $email)->where('token', $token)->first();
            
            if ($invite) {

                $invite->delete();

                Auth::guard('admin')->login($user);

                return 'success';
            }

            return 'failed';
        });

        return response([
            'data' => $user
        ], ($user === 'success') ? Response::HTTP_CREATED : Response::HTTP_NOT_IMPLEMENTED);
    
    }
}