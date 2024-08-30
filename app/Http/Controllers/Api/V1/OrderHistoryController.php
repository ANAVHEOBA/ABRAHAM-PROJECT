<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use App\Http\Resources\OrderResource;
use App\Http\Requests\SearchOrderRequest;
use Illuminate\Http\Request;

class OrderHistoryController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Order::class);
        
        $orders = $this->orderService->getUserOrderHistory($request->user()->id);
        return OrderResource::collection($orders);
    }

    public function show(int $orderId)
    {
        $order = $this->orderService->getOrderDetails($orderId);
        $this->authorize('view', $order);
        
        return new OrderResource($order);
    }

    public function search(SearchOrderRequest $request)
    {
        $this->authorize('viewAny', Order::class);
        
        $orders = $this->orderService->searchOrders($request->validated());
        return OrderResource::collection($orders);
    }
}