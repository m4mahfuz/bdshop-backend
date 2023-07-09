<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdminResource;

class UserController extends Controller
{
    public function __invoke()
    {
        return AdminResource::make(
            auth('admin')->user()
        );
       // return auth('admin')->user();
    }
}