<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Pilot;
use Illuminate\Support\Facades\DB;

class PilotAssignmentService
{
    public function assignPilot(Order $order): ?Pilot
    {
        return DB::transaction(function () use ($order) {
            $pilot = $this->findAvailablePilot($order->pickup_location);

            if (!$pilot) {
                return null;
            }

            $order->update(['pilot_id' => $pilot->id, 'status' => Order::STATUS_ASSIGNED]);
            $pilot->update(['status' => Pilot::STATUS_BUSY]);

            return $pilot;
        });
    }

    protected function findAvailablePilot($pickupLocation): ?Pilot
    {
        return Pilot::available()
            ->orderByDistance($pickupLocation)
            ->lockForUpdate()
            ->first();
    }
}