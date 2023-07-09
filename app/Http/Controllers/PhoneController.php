<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Symfony\Component\HttpFoundation\Response;

class PhoneController extends Controller
{
    public function update(Request $request)
    {
        $this->validateRequest($request);

        $result = $this->isPhoneBeingUsedByOther($request->phone);

        if ($result) {
            // return response()->json([
            //     'message' => 'Sorry! You can not use this phone. Phone is already in use.'
            // ]);

            return response([
                'errors' => [
                    'phone' => ['Oops! The Phone is already in use.']
                ]
                // 'coupon' => [$response]
            ], Response::HTTP_UNAUTHORIZED);
        }

        $request->user()->fill([
            'phone' => $request->phone
        ])->save();
        

        return response([
            'data' => 'Phone changed successfully!'
        ], Response::HTTP_OK );
    }

    public function isPhoneBeingUsedByOther($phone)
    {
        return User::wherePhone($phone)->first();
    }

    public function validateRequest(Request $request) 
    {
        return $request->validate([
            'phone' => 'required|numeric'
        ]);
    }
}