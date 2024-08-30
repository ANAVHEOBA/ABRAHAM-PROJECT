<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use Laravel\Sanctum\Sanctum;

class UserActivityControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    public function test_get_activity_trends()
    {
        User::factory()->count(5)->create(['created_at' => now()->subDays(5)]);
        User::factory()->count(3)->create(['last_login_at' => now()->subDays(3)]);
        Order::factory()->count(10)->create(['created_at' => now()->subDays(2)]);
        Order::factory()->count(2)->create([
            'status' => 'cancelled',
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay()
        ]);

        $response = $this->getJson('/api/user-activity/trends?start_date=' . now()->subWeek()->toDateString() . '&end_date=' . now()->toDateString());

        $response->assertStatus(200)
            ->assertJsonStructure([
                'new_users',
                'active_users',
                'order_placements',
                'order_cancellations'
            ])
            ->assertJson([
                'new_users' => 6,
                'active_users' => 3,
                'order_placements' => 12,
                'order_cancellations' => 2
            ]);
    }

    public function test_get_daily_user_activity()
    {
        User::factory()->count(3)->create(['last_login_at' => now()->subDays(2)]);
        User::factory()->count(2)->create(['last_login_at' => now()->subDay()]);

        $response = $this->getJson('/api/user-activity/daily?start_date=' . now()->subWeek()->toDateString() . '&end_date=' . now()->toDateString());

        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonStructure([
                '*' => ['date', 'active_users']
            ]);
    }

    public function test_get_daily_order_trends()
    {
        Order::factory()->count(5)->create(['created_at' => now()->subDays(2)]);
        Order::factory()->count(3)->create(['created_at' => now()->subDay(), 'status' => 'cancelled']);

        $response = $this->getJson('/api/order-trends/daily?start_date=' . now()->subWeek()->toDateString() . '&end_date=' . now()->toDateString());

        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonStructure([
                '*' => ['date', 'total_orders', 'cancelled_orders']
            ]);
    }
}