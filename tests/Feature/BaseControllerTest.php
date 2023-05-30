<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BaseControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $credentials = [
            'email' => 'admin@gmail.com',
            'password' => 'admin',
        ];
        $response = $this->postJson('/api/login', $credentials);


        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $response['token']])->getJson('/api/preferences');
        // Assert the response
        $response->assertStatus(200);
    }

    public function testAddToUser()
    {
        $credentials = [
            'email' => 'admin@gmail.com',
            'password' => 'admin',
        ];
        $response = $this->postJson('/api/login', $credentials);

        $requestData = [
            'sources' => [1, 2, 3],
            'categories' => [4, 5],
            'authors' => [2, 3],
        ];

        // Call the addToUser method
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $response['token']])->postJson('/api/preferences/addToUser', $requestData);

        // Assert the response
        $response->assertStatus(200);
    }
}
