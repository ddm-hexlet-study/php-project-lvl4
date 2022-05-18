<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Label;
use Tests\TestCase;

class LabelControllerTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
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
        $label = Label::factory()->make()->toArray();
        $response = $this->actingAs($this->user)->post(route('labels.store', $label));
        $response->assertRedirect(route('labels.index'));
        $this->assertDatabaseHas('labels', $label);
    }

    public function testStoreLoggedOut()
    {
        $label = Label::factory()->make()->toArray();
        $response = $this->post(route('labels.store', $label));
        $response->assertStatus(403);
        $this->assertDatabaseMissing('labels', $label);
    }

    public function testUpdateLoggedIn()
    {
        $label = Label::factory()->create();
        $updatedLabel = Label::factory()->make()->toArray();
        $response = $this->actingAs($this->user)->patch(route('labels.update', $label), $updatedLabel);
        $response->assertRedirect(route('labels.index'));
        $this->assertDatabaseHas('labels', $updatedLabel);
        $this->assertDatabaseMissing('labels', $label->toArray());
    }

    public function testUpdateLoggedOut()
    {
        $label = Label::factory()->create();
        $response = $this->patch(route('labels.update', $label));
        $response->assertStatus(403);
    }

    public function testDestroyLoggedIn()
    {
        /** @var Label $label */
        $label = Label::factory()->create();
        $response = $this->actingAs($this->user)->delete(route('labels.destroy', $label));
        $response->assertRedirect(route('labels.index'));
        $this->assertModelMissing($label);
    }

    public function testDestroyLoggedOut()
    {
        /** @var Label $label */
        $label = Label::factory()->create();
        $response = $this->delete(route('labels.destroy', $label));
        $response->assertStatus(403);
        $this->assertModelExists($label);
    }
}
