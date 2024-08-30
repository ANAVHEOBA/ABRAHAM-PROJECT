<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;

class OrderPolicy
{
    public function viewAny(User $user)
    {
        return true; // All authenticated users can view their own orders
    }

    public function view(User $user, Order $order)
    {
        return $user->id === $order->user_id || $user->isAdmin();
    }
}