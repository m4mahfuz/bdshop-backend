<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Rules\CheckPassword;
use App\Rules\ComparePassword;

class PasswordController extends Controller
{
    
    public function check(Request $request)
    {
        // if (Hash::check($request->password, Auth::guard('admin')->user()->password)) {
        //         return response()->json([
        //             'matched' => true
        //         ]);
        // }
        dd('here');
        return response()->json([
            'matched' => false
        ]);
    }

    public function update(Request $request)
    {
        $this->validate($request);

         $request->user()->fill([
            'password' => Hash::make($request->newPassword)
        ])->save();

         return response([
            'data' => 'Updated successfully'
        ], Response::HTTP_OK );

    }

    protected function validate(Request $request) 
    {
        $request->validate([
            'current_password' => ['required', 'string', new checkPassword], 
            'new_password' => ['required', 'string', 'min:6', new comparePassword($request->current_password)], 
            'new_password_confirmation' => 'required|string'
        ]);
    }
        
}