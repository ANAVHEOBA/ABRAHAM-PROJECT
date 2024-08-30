<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Pilot extends Model
{
    use HasFactory;

    // Define statuses
    const STATUS_AVAILABLE = 'available';
    const STATUS_BUSY = 'busy';
    const STATUS_ACTIVE = 'active';
    const STATUS_PROBATION = 'probation';
    const STATUS_ELITE = 'elite';

    // Fillable attributes
    protected $fillable = ['name', 'status', 'current_location'];

    // Define the available scope
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    // Scope to order pilots by distance from a location
    public function scopeOrderByDistance($query, $location)
    {
        // Implement distance calculation logic here
        // This is a simplified example and may need to be adjusted based on your specific requirements
        return $query->orderByRaw("ST_Distance(current_location, ST_GeomFromText(?))", [$location]);
    }

    // Define the relationship with PilotPerformance
    public function performance()
    {
        return $this->hasOne(PilotPerformance::class);
    }
}
