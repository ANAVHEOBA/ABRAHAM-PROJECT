<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Order;
use Illuminate\Support\Facades\Cache;

class OrderService
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function getUserOrderHistory(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return Cache::remember("user_orders_{$userId}_page_{$perPage}", now()->addMinutes(10), function () use ($userId, $perPage) {
            return $this->orderRepository->getUserOrders($userId, $perPage);
        });
    }

    public function getOrderDetails(int $orderId): ?Order
    {
        return Cache::remember("order_details_{$orderId}", now()->addMinutes(10), function () use ($orderId) {
            return $this->orderRepository->getOrder($orderId);
        });
    }

    public function searchOrders(array $criteria, int $perPage = 15): LengthAwarePaginator
    {
        return $this->orderRepository->searchOrders($criteria, $perPage);
    }
}