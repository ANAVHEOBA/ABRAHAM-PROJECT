<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PilotAssignmentService;
use App\Http\Requests\AssignPilotRequest;
use App\Http\Resources\OrderResource;
use Illuminate\Http\JsonResponse;

class PilotAssignmentController extends Controller
{
    protected $pilotAssignmentService;

    public function __construct(PilotAssignmentService $pilotAssignmentService)
    {
        $this->pilotAssignmentService = $pilotAssignmentService;
    }

    public function assignPilot(AssignPilotRequest $request): JsonResponse
    {
        $order = Order::findOrFail($request->order_id);
        $pilot = $this->pilotAssignmentService->assignPilot($order);

        if (!$pilot) {
            return response()->json(['message' => 'No available pilots found'], 404);
        }

        return response()->json([
            'message' => 'Pilot assigned successfully',
            'order' => new OrderResource($order->fresh()),
        ], 200);
    }
}