<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Label;
use Tests\TestCase;

class LabelControllerTest extends TestCase
{
    //private User $user;
    //private Label $label;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->label = Label::factory()->create();
    }

    public function testIndex()
    {
        $response = $this->get(route('labels.index'));
        $response->assertStatus(200);
    }

    public function testCreateLoggedIn()
    {
        $response = $this->actingAs($this->user)->get(route('labels.create'));
        $response->assertStatus(200);
    }

    public function testCreateLoggedOut()
    {
        $response = $this->get(route('labels.create'));
        $response->assertStatus(403);
    }

    public function testStoreLoggedIn()
    {
        $name = $this->faker->lexify();
        $response = $this->actingAs($this->user)->post(route('labels.store', compact('name')));
        $response->assertRedirect(route('labels.index'));
        $this->assertDatabaseHas('labels', ['name' => $name]);
    }

    public function testStoreLoggedOut()
    {
        $name = $this->faker->lexify();
        $response = $this->post(route('labels.store', compact('name')));
        $response->assertStatus(403);
        $this->assertDatabaseMissing('labels', ['name' => $name]);
    }

    public function testUpdateLoggedIn()
    {
        $params = [
            'name' => $this->faker->lexify(),
            'label' => $this->label
        ];
        $response = $this->actingAs($this->user)->patch(route('labels.update', $params));
        $response->assertRedirect(route('labels.index'));
        $this->assertDatabaseHas('labels', ['name' => $params['name']]);
        $this->assertDatabaseMissing('labels', ['name' => $this->label->name]);
    }

    public function testUpdateLoggedOut()
    {
        $params = [
            'name' => $this->faker->lexify(),
            'label' => $this->label
        ];
        $response = $this->patch(route('labels.update', $params));
        $response->assertStatus(403);
        $this->assertDatabaseMissing('labels', ['name' => $params['name']]);
    }

    public function testDestroyLoggedIn()
    {
        $response = $this->actingAs($this->user)->delete(route('labels.destroy', ['label' => $this->label]));
        $response->assertRedirect(route('labels.index'));
        $this->assertDatabaseMissing('labels', ['name' => $this->label->name]);
    }

    public function testDestroyLoggedOut()
    {
        $response = $this->delete(route('labels.destroy', ['label' => $this->label]));
        $response->assertStatus(403);
        $this->assertDatabaseHas('labels', ['name' => $this->label->name]);
    }
}
