<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\sisAPI;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserActivityController;

// Route for getting authenticated user data
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Public routes
Route::get('data', [sisAPI::class, 'getData']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected routes requiring authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/deliveries', [DeliveryController::class, 'index']);
    Route::get('/deliveries/{delivery}', [DeliveryController::class, 'show']);
    Route::put('/deliveries/{delivery}', [DeliveryController::class, 'update']);
    
    Route::get('/analytics/overview', [AnalyticsController::class, 'getOverviewAnalytics']);

    // User Activity Routes
    Route::get('/user-activity/trends', [UserActivityController::class, 'getActivityTrends']);
    Route::get('/user-activity/daily', [UserActivityController::class, 'getDailyUserActivity']);
    Route::get('/order-trends/daily', [UserActivityController::class, 'getDailyOrderTrends']);
});

