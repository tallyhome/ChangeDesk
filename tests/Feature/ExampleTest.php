<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_central_landing_returns_a_successful_response(): void
    {
        $response = $this->get('http://localhost/');

        $response->assertStatus(200);
        $response->assertSee('Evolora');
    }
}
