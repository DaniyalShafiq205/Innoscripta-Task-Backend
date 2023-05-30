<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker, DatabaseMigrations;


    // protected function setUp(): void
    // {
    //     // dd(env('DB_USERNAME'));
    //     parent::setUp();

    //     // Run migrations
    //     $this->artisan('migrate');

    //     // Run seeders
    //     $this->artisan('db:seed');
    // }

    /**
     * Test user registration with valid data.
     */
    public function testUserRegistrationWithValidData()
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure(['token']);
    }

    /**
     * Test user registration with invalid data.
     */
    public function testUserRegistrationWithInvalidData()
    {
        $userData = [
            'name' => '',
            'email' => 'invalid-email',
            'password' => 'short',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(400);
    }

    /**
     * Test user login with valid credentials.
     */
    public function testUserLoginWithValidCredentials()
    {

        $credentials = [
        'email' => 'admin@gmail.com',
        'password' => 'admin',
        ];

        $response = $this->postJson('/api/login', $credentials);
        // dd($response);
        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    /**
     * Test user login with invalid credentials.
     */
    public function testUserLoginWithInvalidCredentials()
    {
        $credentials = [
            'email' => 'invalid-email',
            'password' => 'wrong-password',
        ];

        $response = $this->postJson('/api/login', $credentials);

        $response->assertStatus(400);
    }

    // /**
    //  * Test token refreshing.
    //  */
    public function testTokenRefresh()
    {

        $credentials = [
            'email' => 'admin@gmail.com',
            'password' => 'admin',
        ];
        $response = $this->postJson('/api/login', $credentials);
        $response = $this->withHeaders(['Authorization' => 'Bearer ' .  $response['token']])
            ->postJson('/api/refresh');

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    /**
     * Test user logout.
     */
    public function testUserLogout()
    {

        $user = User::factory()->create(
            [
            'password' => Hash::make('password'),
            ]
        );

        $credentials = [
            'email' => $user->email,
            'password' => 'password',
        ];
        $response = $this->postJson('/api/login', $credentials);
        // dd($response['token']);
        $response = $this->withHeaders(
            [
            'Authorization' => 'Bearer ' . $response['token'],
            ]
        )->postJson('/api/logout');

        $response->assertStatus(200);
    }

    /**
     * Test fetching user details.
     */
    public function testFetchUserDetails()
    {
        $credentials = [
            'email' => 'admin@gmail.com',
            'password' => 'admin',
        ];
        $response = $this->postJson('/api/login', $credentials);


        $response = $this->withHeaders(['Authorization' => 'Bearer ' .$response['token']])
            ->getJson('/api/me');

        $response->assertStatus(200)
            ->assertJsonStructure(['user' => ['id', 'name', 'email', 'sources', 'categories', 'authors']]);
    }
}
