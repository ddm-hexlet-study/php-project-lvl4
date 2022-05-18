<?php

namespace Tests\Feature;

use App\Models\TaskStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Tests\TestCase;

class TaskStatusControllerTest extends TestCase
{
    private User $user;
    private TaskStatus $status;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->status = TaskStatus::factory()->create();
    }
    public function testIndex()
    {
        $response = $this->get(route('task_statuses.index'));
        $response->assertStatus(200);
    }

    public function testCreateLoggedIn()
    {
        $response = $this->actingAs($this->user)->get(route('task_statuses.create'));
        $response->assertStatus(200);
    }

    public function testCreateLoggedOut()
    {
        $response = $this->get(route('task_statuses.create'));
        $response->assertStatus(403);
    }

    public function testStoreLoggedIn()
    {
        $status = TaskStatus::factory()->make()->toArray();
        $response = $this->actingAs($this->user)->post(route('task_statuses.store', $status));
        $response->assertRedirect(route('task_statuses.index'));
        $this->assertDatabaseHas('task_statuses', $status);
    }

    public function testStoreLoggedOut()
    {
        $status = TaskStatus::factory()->make()->toArray();
        $response = $this->post(route('task_statuses.store', $status));
        $response->assertStatus(403);
        $this->assertDatabaseMissing('task_statuses', $status);
    }

    public function testUpdateLoggedIn()
    {
        $updatedStatus = TaskStatus::factory()->make()->toArray();
        $response = $this->actingAs($this->user)->patch(route('task_statuses.update', $this->status), $updatedStatus);
        $response->assertRedirect(route('task_statuses.index'));
        $this->assertDatabaseHas('task_statuses', $updatedStatus);
        $this->assertDatabaseMissing('task_statuses', ['name' => $this->status->name]);
    }

    public function testUpdateLoggedOut()
    {
        $response = $this->patch(route('task_statuses.update', ['task_status' => $this->status]));
        $response->assertStatus(403);
    }

    public function testDestroyLoggedIn()
    {
        $response = $this->actingAs($this->user)
            ->delete(route('task_statuses.destroy', ['task_status' => $this->status]));
        $response->assertRedirect(route('task_statuses.index'));
        $this->assertDatabaseMissing('task_statuses', ['name' => $this->status->name]);
    }

    public function testDestroyLoggedOut()
    {
        $response = $this->delete(route('task_statuses.destroy', ['task_status' => $this->status]));
        $response->assertStatus(403);
    }
}
