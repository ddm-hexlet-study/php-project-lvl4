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
        $this->user = User::factory()->create();
        /** @var Task $task */
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

    public function testStoreLoggedInValidData()
    {
        $task = Task::factory()->for($this->user, 'createdBy')->make()->toArray();
        $response = $this->actingAs($this->user)->post(route('tasks.store', $task));
        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', $task);
    }

    public function testStoreLoggedInInvalidData()
    {
        $task = Task::factory()->for($this->user, 'createdBy')->make(['status_id' => null])->toArray();
        $response = $this->actingAs($this->user)->post(route('tasks.store', $task));
        $response->assertRedirect(route('main'));
    }

    public function testStoreLoggedOut()
    {
        $task = Task::factory()->for($this->user, 'createdBy')->make()->toArray();
        $response = $this->post(route('tasks.store', $task));
        $response->assertStatus(403);
        $this->assertDatabaseMissing('tasks', $task);
    }

    public function testUpdateLoggedInValidData()
    {
        $updatedTask = Task::factory()->for($this->user, 'createdBy')->make()->toArray();
        $response = $this->actingAs($this->user)->patch(route('tasks.update', $this->task->id), $updatedTask);
        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', $updatedTask);
        $this->assertDatabaseMissing('tasks', ['name' => $this->task->name]);
    }

    public function testUpdateLoggedInInvalidData()
    {
        $updatedTask = Task::factory()->for($this->user, 'createdBy')->make(['status_id' => null])->toArray();
        $response = $this->actingAs($this->user)->patch(route('tasks.update', $this->task->id), $updatedTask);
        $response->assertRedirect(route('main'));
        $this->assertDatabaseMissing('tasks', $updatedTask);
        $this->assertDatabaseHas('tasks', ['name' => $this->task->name]);
    }

    public function testUpdateLoggedOut()
    {
        $response = $this->patch(route('tasks.update', ['task' => $this->task]));
        $response->assertStatus(403);
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
    }

    public function testDestroyLoggedOut()
    {
        $response = $this->delete(route('tasks.destroy', ['task' => $this->task]));
        $response->assertStatus(403);
    }
}
