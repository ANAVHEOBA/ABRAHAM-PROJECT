<?php

namespace App\Http\Controllers;

use App\Services\UserActivityService;
use Illuminate\Http\Request;

class UserActivityController extends Controller
{
    protected $userActivityService;

    public function __construct(UserActivityService $userActivityService)
    {
        $this->userActivityService = $userActivityService;
    }

    public function getActivityTrends(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $trends = $this->userActivityService->getUserActivityTrends(
            $request->start_date,
            $request->end_date
        );

        return response()->json($trends);
    }

    public function getDailyUserActivity(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $dailyActivity = $this->userActivityService->getUserActivityByDay(
            $request->start_date,
            $request->end_date
        );

        return response()->json($dailyActivity);
    }

    public function getDailyOrderTrends(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $dailyOrderTrends = $this->userActivityService->getOrderTrendsByDay(
            $request->start_date,
            $request->end_date
        );

        return response()->json($dailyOrderTrends);
    }
}