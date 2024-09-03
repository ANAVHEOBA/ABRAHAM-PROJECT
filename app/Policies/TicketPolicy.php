<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function view(User $user, Ticket $ticket)
    {
        return $user->is_admin || $user->id === $ticket->user_id || $user->id === $ticket->assigned_to;
    }

    public function update(User $user, Ticket $ticket)
    {
        return $user->is_admin || $user->id === $ticket->assigned_to;
    }

    public function delete(User $user, Ticket $ticket)
    {
        return $user->is_admin;
    }

    public function assign(User $user, Ticket $ticket)
    {
        return $user->is_admin;
    }
}