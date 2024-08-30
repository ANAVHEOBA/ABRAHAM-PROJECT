<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Pilot;
use App\Services\PilotAssignmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PilotAssignmentServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PilotAssignmentService();
    }

    public function testAssignPilotSuccess()
    {
        $pilot = Pilot::factory()->create(['status' => Pilot::STATUS_AVAILABLE]);
        $order = Order::factory()->create(['status' => Order::STATUS_PENDING]);

        $assignedPilot = $this->service->assignPilot($order);

        $this->assertNotNull($assignedPilot);
        $this->assertEquals($pilot->id, $assignedPilot->id);
        $this->assertEquals(Pilot::STATUS_BUSY, $assignedPilot->status);
        $this->assertEquals(Order::STATUS_ASSIGNED, $order->fresh()->status);
    }

    public function testAssignPilotNoAvailablePilots()
    {
        Pilot::factory()->create(['status' => Pilot::STATUS_BUSY]);
        $order = Order::factory()->create(['status' => Order::STATUS_PENDING]);

        $assignedPilot = $this->service->assignPilot($order);

        $this->assertNull($assignedPilot);
        $this->assertEquals(Order::STATUS_PENDING, $order->fresh()->status);
    }
}