<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Setting;
use App\Models\Content;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    // Authentication
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $admin->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    // System Settings
    public function updateSystemSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'settings' => 'required|array',
            'settings.*' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        foreach ($request->settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return response()->json(['message' => 'System settings updated successfully'], 200);
    }

    // App Content
    public function updateAppContent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|array',
            'content.*.id' => 'required|integer|exists:contents,id',
            'content.*.body' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        foreach ($request->content as $item) {
            Content::where('id', $item['id'])->update(['body' => $item['body']]);
        }

        return response()->json(['message' => 'App content updated successfully'], 200);
    }

    // Notifications
    public function createNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|string|in:info,warning,error',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $notification = Notification::create($request->all());

        return response()->json(['message' => 'Notification created successfully', 'notification' => $notification], 201);
    }

    public function getNotifications()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->get();
        return response()->json(['notifications' => $notifications], 200);
    }
}
