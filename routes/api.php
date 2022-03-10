<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


use App\Http\Controllers\{
    LoginController,
    LogoutController,
    RegisterController,    
    UserController
};


// Auth ...
Route::post('/login', LoginController::class);
Route::post('/register', RegisterController::class);
Route::post('/logout', LogoutController::class);

// User ...
Route::get('/user', UserController::class)->middleware(['auth:sanctum']);