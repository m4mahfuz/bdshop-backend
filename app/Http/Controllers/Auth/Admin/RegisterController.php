<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
// use App\Http\Resources\ReservationResource;
use App\Models\AdminUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function __invoke()
    {
        request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'unique:admin_users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed'],
            'type_id' => ['required'],
        ]);

        $user = AdminUser::create([
            'name' => request('name'),
            'phone' => request('phone'),
            'email' => request('email'),
            'password' => Hash::make(request('password')),
            'type_id' => request('type_id')
        ]);

        Auth::guard('admin')->login($user);
    }
}