<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);
        
        $users = User::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->paginate(15);
        
        return UserResource::collection($users);
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);
        
        $user->update($request->validated());
        return new UserResource($user);
    }

    public function deactivate(User $user)
    {
        $this->authorize('deactivate', $user);
        
        $user->update(['is_active' => false]);
        return new UserResource($user);
    }

    public function activate(User $user)
    {
        $this->authorize('activate', $user);
        
        $user->update(['is_active' => true]);
        return new UserResource($user);
    }
}