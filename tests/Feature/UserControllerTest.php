<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->user = User::factory()->create();
    }

    public function test_admin_can_view_users_list()
    {
        $response = $this->actingAs($this->admin)->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => [['id', 'name', 'email', 'is_admin', 'is_active']]]);
    }

    public function test_admin_can_view_single_user()
    {
        $response = $this->actingAs($this->admin)->getJson("/api/users/{$this->user->id}");

        $response->assertStatus(200)
            ->assertJson(['data' => ['id' => $this->user->id]]);
    }

    public function test_admin_can_update_user()
    {
        $response = $this->actingAs($this->admin)->putJson("/api/users/{$this->user->id}", [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJson(['data' => [
                'name' => 'Updated Name',
                'email' => 'updated@example.com',
            ]]);
    }

    public function test_admin_can_deactivate_user()
    {
        $response = $this->actingAs($this->admin)->postJson("/api/users/{$this->user->id}/deactivate");

        $response->assertStatus(200)
            ->assertJson(['data' => ['is_active' => false]]);
    }

    public function test_admin_can_activate_user()
    {
        $this->user->update(['is_active' => false]);

        $response = $this->actingAs($this->admin)->postJson("/api/users/{$this->user->id}/activate");

        $response->assertStatus(200)
            ->assertJson(['data' => ['is_active' => true]]);
    }

    public function test_non_admin_cannot_access_user_management()
    {
        $response = $this->actingAs($this->user)->getJson('/api/users');
        $response->assertStatus(403);
    }
}