<?php

namespace App\Http\Middleware;

use App\Services\AdminService;
use Illuminate\Http\Request;
use Closure;

class CheckAdmin
{
    
    private $user;

    public function __construct(AdminService $user)
    {
        $this->user = $user;
    }    

    public function handle(Request $request, Closure $next, ...$roles)
    {
        $this->user->authorizedAdmin($roles);

        return $next($request);
    }
}
