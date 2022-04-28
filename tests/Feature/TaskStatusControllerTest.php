<?php

namespace Tests\Feature;

use App\Models\TaskStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Tests\TestCase;

class TaskStatusControllerTest extends TestCase
{
    //private User $user;
    //private int $taskId;
    //private string $taskName;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->taskName = $this->faker->lexify();
        $this->taskId = DB::table('task_statuses')->insertGetId([
            'id' => 1,
            'name' => "{$this->taskName}",
            'created_at' => now()
        ]);
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
        $taskName = $this->faker->lexify();
        $response = $this->actingAs($this->user)->post(route('task_statuses.store', ['name' => $taskName]));
        $response->assertRedirect(route('task_statuses.index'));
        $this->assertDatabaseHas('task_statuses', ['name' => $taskName]);
    }

    public function testStoreLoggedOut()
    {
        $taskName = $this->faker->lexify();
        $response = $this->post(route('task_statuses.store', ['name' => $taskName]));
        $response->assertStatus(403);
        $this->assertDatabaseMissing('task_statuses', ['name' => $taskName]);
    }

    public function testUpdateLoggedIn()
    {
        $params = [
            'name' => $this->faker->lexify(),
            'task_status' => $this->taskId
        ];
        $response = $this->actingAs($this->user)->patch(route('task_statuses.update', $params));
        $response->assertRedirect(route('task_statuses.index'));
        $this->assertDatabaseHas('task_statuses', ['name' => $params['name']]);
        $this->assertDatabaseMissing('task_statuses', ['name' => $this->taskName]);
    }

    public function testUpdateLoggedOut()
    {
        $params = [
            'name' => $this->faker->lexify(),
            'task_status' => $this->taskId
        ];
        $response = $this->patch(route('task_statuses.update', $params));
        $response->assertStatus(403);
        $this->assertDatabaseMissing('task_statuses', ['name' => $params['name']]);
    }

    public function testDestroyLoggedIn()
    {
        $response = $this->actingAs($this->user)
            ->delete(route('task_statuses.destroy', ['task_status' => $this->taskId]));
        $response->assertRedirect(route('task_statuses.index'));
        $this->assertDatabaseMissing('task_statuses', ['name' => $this->taskName]);
    }

    public function testDestroyLoggedOut()
    {
        $response = $this->delete(route('task_statuses.destroy', ['task_status' => $this->taskId]));
        $response->assertStatus(403);
        $this->assertDatabaseHas('task_statuses', ['name' => $this->taskName]);
    }
}
