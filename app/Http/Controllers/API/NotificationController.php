<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNotificationRequest;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 15);

        $cacheKey = 'notifications:' . $request->user()->id . ':' . $page . ':' . $perPage;

        $notifications = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($request, $perPage) {
            return $request->user()->notifications()->latest()->paginate($perPage);
        });

        return NotificationResource::collection($notifications);
    }

    public function store(StoreNotificationRequest $request)
    {
        $notification = $request->user()->notifications()->create($request->validated());
        
        Cache::forget('notifications:' . $request->user()->id . ':1:15');

        return new NotificationResource($notification);
    }

    public function update(Request $request, Notification $notification)
    {
        $this->authorize('update', $notification);

        $request->validate([
            'read' => 'required|boolean',
        ]);

        $notification->update([
            'read' => $request->read,
            'read_at' => $request->read ? now() : null,
        ]);

        Cache::forget('notifications:' . $request->user()->id . ':1:15');

        return new NotificationResource($notification);
    }
}