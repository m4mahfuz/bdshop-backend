<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

class DashboardController extends Controller
{
    
    public function __invoke()
    {
        return response()->json([
            'data' => 'Hello World'
        ]);
    }

    
}