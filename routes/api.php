<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\OrderHistoryController;
use App\Http\Controllers\PilotPerformanceController;
use App\Http\Controllers\PilotAssignmentController;
use App\Http\Controllers\sisAPI;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserActivityController;
use App\Http\Controllers\UserController;

// Public routes
Route::get('data', [sisAPI::class, 'getData']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Route for getting authenticated user data
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Protected routes requiring authentication
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
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
});

// Versioned API routes
Route::prefix('v1')->middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::get('/orders', [OrderHistoryController::class, 'index']);
    Route::get('/orders/{order}', [OrderHistoryController::class, 'show']);
    Route::post('/orders/search', [OrderHistoryController::class, 'search']);
});
