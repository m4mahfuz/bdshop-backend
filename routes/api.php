<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


use App\Http\Controllers\{
    // /Auth\Admin\RegisterController as AdminRegisterController,    
    Auth\LoginController,
    Auth\LogoutController,
    Auth\RegisterController,    
    CategoryController,
    UserController
};

// Auth Admin ...
// Route::prefix('admin')->group(function () {    
//     Route::post('/register', AdminRegisterController::class)->name('admin.register');
// });

// Auth ...
Route::post('/login', LoginController::class)->name('login');
Route::post('/register', RegisterController::class)->name('register');
Route::post('/logout', LogoutController::class)->name('logout');

// User ...
Route::get('/user', UserController::class)->middleware(['auth:sanctum']);

//Category
Route::apiResource('categories', CategoryController::class);