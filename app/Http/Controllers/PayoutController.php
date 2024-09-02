<?php

namespace App\Http\Controllers;

use App\Http\Requests\InitiatePayoutRequest;
use App\Http\Resources\PayoutResource;
use App\Models\Payout;
use App\Services\PayoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PayoutController extends Controller
{
    protected $payoutService;

    public function __construct(PayoutService $payoutService)
    {
        $this->payoutService = $payoutService;
    }

    public function index(): AnonymousResourceCollection
    {
        $payouts = $this->payoutService->getAllPayouts();
        return PayoutResource::collection($payouts);
    }

    public function show(Payout $payout): PayoutResource
    {
        return new PayoutResource($payout);
    }

    public function initiate(InitiatePayoutRequest $request): JsonResponse
    {
        $payout = $this->payoutService->initiatePayout($request->validated());
        return (new PayoutResource($payout))
            ->response()
            ->setStatusCode(201);
    }

    public function getPilotPayouts(int $pilotId): AnonymousResourceCollection
    {
        $payouts = $this->payoutService->getPilotPayouts($pilotId);
        return PayoutResource::collection($payouts);
    }
}