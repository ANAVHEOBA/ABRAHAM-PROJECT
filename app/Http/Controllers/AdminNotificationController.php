<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\CriticalEventNotification;
use App\Notifications\SystemUpdateNotification;
use App\Notifications\HighPriorityActivityNotification;
use App\Http\Requests\CreateNotificationRequest;

class AdminNotificationController extends Controller
{
    public function sendNotification(CreateNotificationRequest $request)
    {
        $data = $request->validated();

        $notification = Notification::create($data);

        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $this->notifyAdmin($admin, $notification);
        }

        return response()->json(['message' => 'Notification sent successfully'], 201);
    }

    private function notifyAdmin(User $admin, Notification $notification)
    {
        switch ($notification->type) {
            case 'critical_event':
                $admin->notify(new CriticalEventNotification($notification));
                break;
            case 'system_update':
                $admin->notify(new SystemUpdateNotification($notification));
                break;
            case 'high_priority_activity':
                $admin->notify(new HighPriorityActivityNotification($notification));
                break;
        }
    }

    public function getNotifications(Request $request)
    {
        $notifications = $request->user()->notifications()->paginate(15);
        return response()->json($notifications);
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return response()->json(['message' => 'Notification marked as read']);
    }
}
