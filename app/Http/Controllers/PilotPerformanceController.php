<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePilotPerformanceRequest;
use App\Http\Resources\PilotPerformanceResource;
use App\Models\Pilot;
use App\Services\PilotPerformanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PilotPerformanceController extends Controller
{
    protected $pilotPerformanceService;

    public function __construct(PilotPerformanceService $pilotPerformanceService)
    {
        $this->pilotPerformanceService = $pilotPerformanceService;
    }

    public function index(): AnonymousResourceCollection
    {
        $performances = $this->pilotPerformanceService->getAllPerformances();
        return PilotPerformanceResource::collection($performances);
    }

    public function show(Pilot $pilot): PilotPerformanceResource
    {
        $performance = $this->pilotPerformanceService->getPilotPerformance($pilot);
        return new PilotPerformanceResource($performance);
    }

    public function store(StorePilotPerformanceRequest $request): JsonResponse
    {
        $performance = $this->pilotPerformanceService->recordPerformance($request->validated());
        return (new PilotPerformanceResource($performance))
            ->response()
            ->setStatusCode(201);
    }
}