<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user)
    {
        return $user->is_admin;
    }

    public function view(User $user, User $model)
    {
        return $user->is_admin;
    }

    public function update(User $user, User $model)
    {
        return $user->is_admin;
    }

    public function deactivate(User $user, User $model)
    {
        return $user->is_admin && $user->id !== $model->id;
    }

    public function activate(User $user, User $model)
    {
        return $user->is_admin;
    }
}