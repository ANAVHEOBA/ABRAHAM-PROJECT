<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository
{
    public function getUserOrders(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return Order::with(['items.product'])
            ->where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }

    public function getOrder(int $orderId): ?Order
    {
        return Order::with(['items.product', 'user'])
            ->findOrFail($orderId);
    }

    public function searchOrders(array $criteria, int $perPage = 15): LengthAwarePaginator
    {
        $query = Order::query();

        if (isset($criteria['user_id'])) {
            $query->where('user_id', $criteria['user_id']);
        }

        if (isset($criteria['status'])) {
            $query->where('status', $criteria['status']);
        }

        if (isset($criteria['date_from'])) {
            $query->where('created_at', '>=', $criteria['date_from']);
        }

        if (isset($criteria['date_to'])) {
            $query->where('created_at', '<=', $criteria['date_to']);
        }

        return $query->with(['items.product', 'user'])
            ->latest()
            ->paginate($perPage);
    }
}