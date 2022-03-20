<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

class LoginController extends Controller
{
    
    public function __invoke()
    {
        $credentials = request()->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required'],
        ]);

        /**
         * We are authenticating a request from our frontend.
         */
        if (EnsureFrontendRequestsAreStateful::fromFrontend(request())) {
            $this->authenticateFrontendWith($credentials);
        }
        /**
         * We are authenticating a request from a 3rd party.
         */
        else {
            // Use token authentication.
        }
    }

    private function authenticateFrontendWith(Array $credentials)
    {
        if (! Auth::guard('admin')
            ->attempt(
                $credentials,
                request()->boolean('remember')
            )) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }
    }
}