<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Delivery;
use App\Models\User;

class AnalyticsController extends Controller
{
    public function getOverviewAnalytics()
    {
        $totalOrders = Order::count();
        $completedDeliveries = Delivery::where('status', 'completed')->count();
        $totalEarnings = Order::sum('total_amount');
        $activeUsers = User::where('last_activity', '>=', now()->subDays(30))->count();

        return response()->json([
            'total_orders' => $totalOrders,
            'completed_deliveries' => $completedDeliveries,
            'total_earnings' => $totalEarnings,
            'active_users' => $activeUsers,
        ]);
    }
}