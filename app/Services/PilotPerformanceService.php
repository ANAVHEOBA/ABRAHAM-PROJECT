<?php

namespace App\Services;

use App\Models\Pilot;
use App\Models\PilotPerformance;
use Illuminate\Support\Facades\DB;

class PilotPerformanceService
{
    public function getAllPerformances()
    {
        return PilotPerformance::with('pilot')->get();
    }

    public function getPilotPerformance(Pilot $pilot)
    {
        return PilotPerformance::where('pilot_id', $pilot->id)->firstOrFail();
    }

    public function recordPerformance(array $data)
    {
        return DB::transaction(function () use ($data) {
            $performance = PilotPerformance::updateOrCreate(
                ['pilot_id' => $data['pilot_id']],
                [
                    'completed_deliveries' => DB::raw('completed_deliveries + 1'),
                    'total_ratings' => DB::raw('total_ratings + ' . $data['rating']),
                    'average_rating' => DB::raw('(total_ratings + ' . $data['rating'] . ') / (completed_deliveries + 1)'),
                ]
            );

            $this->updatePilotStatus($performance);

            return $performance->fresh();
        });
    }

    protected function updatePilotStatus(PilotPerformance $performance)
    {
        $pilot = $performance->pilot;
        
        if ($performance->average_rating < 3.0) {
            $pilot->status = Pilot::STATUS_PROBATION;
        } elseif ($performance->completed_deliveries > 100 && $performance->average_rating >= 4.5) {
            $pilot->status = Pilot::STATUS_ELITE;
        } else {
            $pilot->status = Pilot::STATUS_ACTIVE;
        }

        $pilot->save();
    }
}