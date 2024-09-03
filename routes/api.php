<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\Api\V1\OrderHistoryController;
use App\Http\Controllers\PilotPerformanceController;
use App\Http\Controllers\PilotAssignmentController;
use App\Http\Controllers\sisAPI;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\UserActivityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PayoutController;
use App\Http\Controllers\AdminNotificationController; // Added

// Public routes
Route::get('data', [sisAPI::class, 'getData']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Route for getting authenticated user data
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Authenticated routes with Sanctum middleware
Route::middleware(['auth:sanctum'])->group(function () {
    // AuthController routes
    Route::post('/logout', [AuthController::class, 'logout']);

    // Ticket routes
    Route::apiResource('tickets', TicketController::class);
    Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assign']);
    Route::post('/tickets/{ticket}/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);

    // Notification routes (using NotificationController)
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications', [NotificationController::class, 'store']);
    Route::put('/notifications/{notification}', [NotificationController::class, 'update']);

    // Delivery routes
    Route::get('/deliveries', [DeliveryController::class, 'index']);
    Route::get('/deliveries/{delivery}', [DeliveryController::class, 'show']);
    Route::put('/deliveries/{delivery}', [DeliveryController::class, 'update']);

    // Analytics routes
    Route::get('/analytics/overview', [AnalyticsController::class, 'getOverviewAnalytics']);

    // User Activity routes
    Route::get('/user-activity/trends', [UserActivityController::class, 'getActivityTrends']);
    Route::get('/user-activity/daily', [UserActivityController::class, 'getDailyUserActivity']);
    Route::get('/order-trends/daily', [UserActivityController::class, 'getDailyOrderTrends']);

    // User management routes
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::post('/users/{user}/deactivate', [UserController::class, 'deactivate']);
    Route::post('/users/{user}/activate', [UserController::class, 'activate']);

    // Pilot assignment route
    Route::post('/assign-pilot', [PilotAssignmentController::class, 'assignPilot']);

    // Pilot performance routes
    Route::prefix('pilot-performances')->group(function () {
        Route::get('/', [PilotPerformanceController::class, 'index']);
        Route::get('/{pilot}', [PilotPerformanceController::class, 'show']);
        Route::post('/', [PilotPerformanceController::class, 'store']);
    });

    // Transaction routes
    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index']);
        Route::get('/{transaction}', [TransactionController::class, 'show']);
        Route::post('/', [TransactionController::class, 'store']);
    });

    Route::get('/users/{user}/transactions', [TransactionController::class, 'getUserTransactions']);
    Route::get('/pilots/{pilot}/transactions', [TransactionController::class, 'getPilotTransactions']);

    // Admin Notification routes (using AdminNotificationController)
    Route::post('/admin/notifications', [AdminNotificationController::class, 'sendNotification']);
    Route::get('/admin/notifications', [AdminNotificationController::class, 'getNotifications']);
    Route::post('/admin/notifications/{id}/read', [AdminNotificationController::class, 'markAsRead']);
});

// Admin routes with throttling
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::post('/admin/login', [AdminController::class, 'login']);
    Route::post('/admin/logout', [AdminController::class, 'logout']);
    Route::post('/admin/settings', [AdminController::class, 'updateSystemSettings']);
    Route::post('/admin/content', [AdminController::class, 'updateAppContent']);
    Route::post('/admin/notifications', [AdminController::class, 'createNotification']);
    Route::get('/admin/notifications', [AdminController::class, 'getNotifications']);
});

// Versioned API routes
Route::prefix('v1')->middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::get('/orders', [OrderHistoryController::class, 'index']);
    Route::get('/orders/{order}', [OrderHistoryController::class, 'show']);
    Route::post('/orders/search', [OrderHistoryController::class, 'search']);
});

// Payout routes
Route::prefix('payouts')->group(function () {
    Route::get('/', [PayoutController::class, 'index']);
    Route::get('/{payout}', [PayoutController::class, 'show']);
    Route::post('/initiate', [PayoutController::class, 'initiate']);
    Route::get('/pilots/{pilot}/payouts', [PayoutController::class, 'getPilotPayouts']);
});


