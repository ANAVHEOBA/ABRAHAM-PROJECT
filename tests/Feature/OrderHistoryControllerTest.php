<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderHistoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_view_their_order_history()
    {
        Order::factory()->count(5)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->getJson('/api/v1/orders');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'user_id', 'total', 'status', 'created_at', 'updated_at']
                ],
                'links',
                'meta'
            ]);
    }

    public function test_user_can_view_specific_order_details()
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->getJson("/api/v1/orders/{$order->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $order->id,
                    'user_id' => $this->user->id,
                ]
            ]);
    }

    public function test_user_cannot_view_other_users_order()
    {
        $otherUser = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)->getJson("/api/v1/orders/{$order->id}");

        $response->assertStatus(403);
    }

    public function test_user_can_search_their_orders()
    {
        Order::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'status' => 'completed'
        ]);
        Order::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->user)->postJson('/api/v1/orders/search', [
            'status' => 'completed'
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }
}