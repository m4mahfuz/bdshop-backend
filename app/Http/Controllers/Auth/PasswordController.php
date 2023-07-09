<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Rules\CheckPassword;
use App\Rules\ComparePassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class PasswordController extends Controller
{
    
    public function check(Request $request)
    {
        if (Hash::check($request->password, Auth::guard('web')->user()->password)) {
            
            return response()->json([
                'matched' => true
            ]);            
        }

        return response()->json([
            'matched' => false
        ]);
    }

    public function update(Request $request)
    {
        $this->validateRequest($request);

         $request->user()->fill([
            'password' => Hash::make($request->new_password)
        ])->save();

         return response([
            'data' => 'Password updated successfully!'
        ], Response::HTTP_OK );

    }

    public function validateRequest(Request $request) 
    {
        return $request->validate([
            'current_password' => ['required', 'string', new checkPassword('web')], 
            'new_password' => ['required', 'confirmed', 'string', 'min:6', new comparePassword($request->current_password)], 
            'new_password_confirmation' => 'required|string'
        ]);
    }
        
}