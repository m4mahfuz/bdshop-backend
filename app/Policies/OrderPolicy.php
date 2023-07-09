<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;
    
    public function manage(User $user, Order $order)
    {
        // ddd('here');
        return $user->id === $order->user_id
                ? Response::allow()
                : Response::deny('You do not own this order.');
    }
}
