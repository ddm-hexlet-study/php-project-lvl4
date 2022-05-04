<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    private User $user;
    private mixed $task;

    protected function setUp(): void
    {
        parent::setUp();
        /** @var User $this->user */
        $this->user = User::factory()->create();
        //$this->user = User::find(1);
        $this->task = Task::factory()
            ->for($this->user, 'createdBy')->create();
        //$this->task = $task;
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
        $name = $this->faker->lexify();
        $status_id = 1;
        $response = $this->actingAs($this->user)->post(route('tasks.store', compact('name', 'status_id')));
        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', ['name' => $name]);
    }

    public function testStoreLoggedInInvalidData()
    {
        $name = $this->faker->lexify();
        $status_id = 1;
        $response = $this->actingAs($this->user)->post(route('tasks.store', compact('status_id')));
        $response->assertRedirect(route('main'));
        $this->assertDatabaseMissing('tasks', ['name' => $name]);
    }

    public function testStoreLoggedOut()
    {
        $name = $this->faker->lexify();
        $status_id = 1;
        $response = $this->post(route('tasks.store', compact('name', 'status_id')));
        $response->assertStatus(403);
        $this->assertDatabaseMissing('tasks', ['name' => $name]);
    }

    public function testUpdateLoggedInValidData()
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

    public function testUpdateLoggedInInvalidData()
    {
        $params = [
            'name' => $this->faker->lexify(),
            'task' => $this->task
        ];
        $response = $this->actingAs($this->user)->patch(route('tasks.update', $params));
        $response->assertRedirect(route('main'));
        $this->assertDatabaseMissing('tasks', ['name' => $params['name']]);
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
