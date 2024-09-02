<?php

namespace Tests\Feature;

use App\Models\Pilot;
use App\Models\Payout;
use App\Models\Delivery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PayoutControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        Payout::factory()->count(5)->create();

        $response = $this->getJson('/api/payouts');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'pilot', 'amount', 'status', 'processed_at', 'created_at', 'updated_at']
                ]
            ]);
    }

    public function testShow()
    {
        $payout = Payout::factory()->create();

        $response = $this->getJson("/api/payouts/{$payout->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $payout->id,
                    'amount' => $payout->amount,
                    'status' => $payout->status,
                ]
            ]);
    }

    public function testInitiate()
    {
        $pilot = Pilot::factory()->create();
        Delivery::factory()->count(5)->create([
            'pilot_id' => $pilot->id,
            'status' => 'completed'
        ]);

        $response = $this->postJson('/api/payouts/initiate', [
            'pilot_id' => $pilot->id,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id', 'pilot', 'amount', 'status', 'processed_at', 'created_at', 'updated_at'
                ]
            ]);

        $this->assertDatabaseHas('payouts', [
            'pilot_id' => $pilot->id,
            'status' => Payout::STATUS_PENDING,
        ]);
    }

    public function testGetPilotPayouts()
    {
        $pilot = Pilot::factory()->create();
        Payout::factory()->count(3)->create(['pilot_id' => $pilot->id]);

        $response = $this->getJson("/api/pilots/{$pilot->id}/payouts");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'pilot', 'amount', 'status', 'processed_at', 'created_at', 'updated_at']
                ]
            ]);
    }
}