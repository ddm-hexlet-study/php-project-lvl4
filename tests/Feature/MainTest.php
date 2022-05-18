<?php

namespace Tests\Feature;

use Tests\TestCase;

class MainTest extends TestCase
{
    public function testMain()
    {
        $response = $this->get(route('main'));
        $response->assertStatus(200);
    }
}
