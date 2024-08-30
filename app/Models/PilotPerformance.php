<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PilotPerformance extends Model
{
    use HasFactory;

    protected $fillable = ['pilot_id', 'completed_deliveries', 'total_ratings', 'average_rating'];

    public function pilot()
    {
        return $this->belongsTo(Pilot::class);
    }
}