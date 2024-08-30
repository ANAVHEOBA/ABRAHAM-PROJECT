<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PilotPerformanceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'pilot' => [
                'id' => $this->pilot->id,
                'name' => $this->pilot->name,
                'status' => $this->pilot->status,
            ],
            'completed_deliveries' => $this->completed_deliveries,
            'average_rating' => $this->average_rating,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}