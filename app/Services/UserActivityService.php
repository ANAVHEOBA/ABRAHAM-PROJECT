<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserActivityService
{
    public function getUserActivityTrends($startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        return [
            'new_users' => $this->getNewUsersCount($startDate, $endDate),
            'active_users' => $this->getActiveUsersCount($startDate, $endDate),
            'order_placements' => $this->getOrderPlacementsCount($startDate, $endDate),
            'order_cancellations' => $this->getOrderCancellationsCount($startDate, $endDate),
        ];
    }

    private function getNewUsersCount($startDate, $endDate)
    {
        return User::whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    private function getActiveUsersCount($startDate, $endDate)
    {
        return User::whereBetween('last_login_at', [$startDate, $endDate])
            ->count();
    }

    private function getOrderPlacementsCount($startDate, $endDate)
    {
        return Order::whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    private function getOrderCancellationsCount($startDate, $endDate)
    {
        return Order::where('status', 'cancelled')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->count();
    }

    public function getUserActivityByDay($startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        return DB::table('users')
            ->select(DB::raw('DATE(last_login_at) as date'), DB::raw('COUNT(*) as active_users'))
            ->whereBetween('last_login_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function getOrderTrendsByDay($startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        return DB::table('orders')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled_orders')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
}