<?php

namespace Tests\Feature;

use App\Models\Pilot;
use App\Models\PilotPerformance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PilotPerformanceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        PilotPerformance::factory()->count(3)->create();

        $response = $this->getJson('/api/pilot-performances');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'pilot', 'completed_deliveries', 'average_rating', 'created_at', 'updated_at']
                ]
            ]);
    }

    public function testShow()
    {
        $pilot = Pilot::factory()->create();
        $performance = PilotPerformance::factory()->create(['pilot_id' => $pilot->id]);

        $response = $this->getJson("/api/pilot-performances/{$pilot->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $performance->id,
                    'pilot' => [
                        'id' => $pilot->id,
                        'name' => $pilot->name,
                        'status' => $pilot->status,
                    ],
                    'completed_deliveries' => $performance->completed_deliveries,
                    'average_rating' => $performance->average_rating,
                ]
            ]);
    }

    public function testStore()
    {
        $pilot = Pilot::factory()->create();

        $response = $this->postJson('/api/pilot-performances', [
            'pilot_id' => $pilot->id,
            'rating' => 4,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'pilot' => [
                        'id' => $pilot->id,
                        'name' => $pilot->name,
                        'status' => $pilot->status,
                    ],
                    'completed_deliveries' => 1,
                    'average_rating' => 4.00,
                ]
            ]);

        $this->assertDatabaseHas('pilot_performances', [
            'pilot_id' => $pilot->id,
            'completed_deliveries' => 1,
            'total_ratings' => 4,
            'average_rating' => 4.00,
        ]);
    }
}