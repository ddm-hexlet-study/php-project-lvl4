<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    private User $user;
    private Task $task;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $this->user = $user;
        $task = Task::factory()
            ->for($this->user, 'createdBy')->create();
        $this->task = $task;
    }

    public function testIndex()
    {
        $response = $this->get(route('tasks.index'));
        $response->assertStatus(200);
    }

    public function testCreateLoggedIn()
    {
        $response = $this->actingAs($this->user)->get(route('tasks.create'));
        $response->assertStatus(200);
    }

    public function testCreateLoggedOut()
    {
        $response = $this->get(route('tasks.create'));
        $response->assertStatus(403);
    }

    public function testStoreLoggedIn()
    {
        $name = $this->faker->lexify();
        $status_id = 1;

        $response = $this->actingAs($this->user)->post(route('tasks.store', compact('name', 'status_id')));
        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', ['name' => $name]);
        $response = $this->actingAs($this->user)->post(route('tasks.store', compact('status_id')));
        $response->assertRedirect(route('tasks.create'));
    }

    public function testStoreLoggedOut()
    {
        $name = $this->faker->lexify();
        $status_id = 1;
        $response = $this->post(route('tasks.store', compact('name', 'status_id')));
        $response->assertStatus(403);
        $this->assertDatabaseMissing('tasks', ['name' => $name]);
    }

    public function testUpdateLoggedIn()
    {
        $params = [
            'name' => $this->faker->lexify(),
            'status_id' => $this->task->id,
            'task' => $this->task
        ];
        $response = $this->actingAs($this->user)->patch(route('tasks.update', $params));
        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', ['name' => $params['name']]);
        $this->assertDatabaseMissing('tasks', ['name' => $this->task->name]);
    }

    public function testUpdateLoggedOut()
    {
        $params = [
            'name' => $this->faker->lexify(),
            'status_id' => $this->task->id,
            'task' => $this->task
        ];
        $response = $this->patch(route('tasks.update', $params));
        $response->assertStatus(403);
        $this->assertDatabaseMissing('tasks', ['name' => $params['name']]);
    }

    public function testDestroyLoggedInAsOwner()
    {
        $response = $this->actingAs($this->user)->delete(route('tasks.destroy', ['task' => $this->task]));
        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseMissing('tasks', ['name' => $this->task->name]);
    }

    public function testDestroyLoggedInAsOther()
    {
        $otherUser = User::factory()->create();
        $response = $this->actingAs($otherUser)->delete(route('tasks.destroy', ['task' => $this->task]));
        $response->assertStatus(403);
        $this->assertDatabaseHas('tasks', ['name' => $this->task->name]);
    }

    public function testDestroyLoggedOut()
    {
        $response = $this->delete(route('tasks.destroy', ['task' => $this->task]));
        $response->assertStatus(403);
        $this->assertDatabaseHas('tasks', ['name' => $this->task->name]);
    }
}
