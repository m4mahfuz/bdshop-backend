<?php

namespace App\Http\Middleware;

use App\Services\UserService;
use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    private $user;

    public function __construct(UserService $user)
    {
        $this->user = $user;
    }    

    public function handle(Request $request, Closure $next, ...$roles)
    {
        $this->user->authorizeRoles($roles);

        return $next($request);
    }
}
