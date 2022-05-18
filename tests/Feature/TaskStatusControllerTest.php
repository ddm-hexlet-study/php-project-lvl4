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
        $status = TaskStatus::factory()->create();
        $updatedStatus = TaskStatus::factory()->make()->toArray();
        $response = $this->actingAs($this->user)->patch(route('task_statuses.update', $status), $updatedStatus);
        $response->assertRedirect(route('task_statuses.index'));
        $this->assertDatabaseHas('task_statuses', $updatedStatus);
        $this->assertDatabaseMissing('task_statuses', $status->toArray());
    }

    public function testUpdateLoggedOut()
    {
        $status = TaskStatus::factory()->create();
        $response = $this->patch(route('task_statuses.update', $status));
        $response->assertStatus(403);
    }

    public function testDestroyLoggedIn()
    {
        $status = TaskStatus::factory()->create();
        $response = $this->actingAs($this->user)
            ->delete(route('task_statuses.destroy', $status));
        $response->assertRedirect(route('task_statuses.index'));
        $this->assertModelMissing($status);
    }

    public function testDestroyLoggedOut()
    {
        $status = TaskStatus::factory()->create();
        $response = $this->delete(route('task_statuses.destroy', $status));
        $this->assertModelExists($status);
        $response->assertStatus(403);
    }
}
