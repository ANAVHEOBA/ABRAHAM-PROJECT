<?php

namespace App\Services;

use App\Models\Payout;
use App\Models\Pilot;
use App\Jobs\ProcessPayout;
use App\Notifications\PayoutInitiated;
use Illuminate\Support\Facades\DB;

class PayoutService
{
    public function getAllPayouts()
    {
        return Payout::with('pilot')->latest()->paginate(20);
    }

    public function getPilotPayouts(int $pilotId)
    {
        return Payout::where('pilot_id', $pilotId)->latest()->paginate(20);
    }

    public function initiatePayout(array $data)
    {
        return DB::transaction(function () use ($data) {
            $pilot = Pilot::findOrFail($data['pilot_id']);
            
            // Calculate payout amount based on completed deliveries
            $amount = $this->calculatePayoutAmount($pilot);

            $payout = Payout::create([
                'pilot_id' => $pilot->id,
                'amount' => $amount,
                'status' => Payout::STATUS_PENDING,
            ]);

            // Dispatch job to process payout
            ProcessPayout::dispatch($payout);

            // Notify pilot
            $pilot->notify(new PayoutInitiated($payout));

            return $payout;
        });
    }

    protected function calculatePayoutAmount(Pilot $pilot)
    {
        // Implement your payout calculation logic here
        // This is a simplified example
        // you can add other calculations here 
        // Abraham did this 
        $completedDeliveries = $pilot->deliveries()->completed()->count();
        $ratePerDelivery = 10; // $10 per delivery
        return $completedDeliveries * $ratePerDelivery;
    }
}