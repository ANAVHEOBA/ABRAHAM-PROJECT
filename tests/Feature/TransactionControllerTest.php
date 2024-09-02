<?php

namespace Tests\Feature;

use App\Models\Pilot;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        Transaction::factory()->count(5)->create();

        $response = $this->getJson('/api/transactions');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'user', 'pilot', 'amount', 'type', 'status', 'description', 'created_at', 'updated_at']
                ]
            ]);
    }

    public function testShow()
    {
        $transaction = Transaction::factory()->create();

        $response = $this->getJson("/api/transactions/{$transaction->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $transaction->id,
                    'amount' => $transaction->amount,
                    'type' => $transaction->type,
                    'status' => $transaction->status,
                    'description' => $transaction->description,
                ]
            ]);
    }

    public function testStore()
    {
        $user = User::factory()->create();
        $pilot = Pilot::factory()->create();

        $data = [
            'user_id' => $user->id,
            'pilot_id' => $pilot->id,
            'amount' => 100.00,
            'type' => Transaction::TYPE_PAYMENT,
            'description' => 'Test payment',
        ];

        $response = $this->postJson('/api/transactions', $data);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                    ],
                    'pilot' => [
                        'id' => $pilot->id,
                        'name' => $pilot->name,
                    ],
                    'amount' => 100.00,
                    'type' => Transaction::TYPE_PAYMENT,
                    'status' => Transaction::STATUS_PENDING,
                    'description' => 'Test payment',
                ]
            ]);

        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'pilot_id' => $pilot->id,
            'amount' => 100.00,
            'type' => Transaction::TYPE_PAYMENT,
            'status' => Transaction::STATUS_PENDING,
            'description' => 'Test payment',
        ]);
    }

    public function testGetUserTransactions()
    {
        $user = User::factory()->create();
        Transaction::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/users/{$user->id}/transactions");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'user', 'pilot', 'amount', 'type', 'status', 'description', 'created_at', 'updated_at']
                ]
            ]);
    }

    public function testGetPilotTransactions()
    {
        $pilot = Pilot::factory()->create();
        Transaction::factory()->count(3)->create(['pilot_id' => $pilot->id]);

        $response = $this->getJson("/api/pilots/{$pilot->id}/transactions");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'user', 'pilot', 'amount', 'type', 'status', 'description', 'created_at', 'updated_at']
                ]
            ]);
    }
}