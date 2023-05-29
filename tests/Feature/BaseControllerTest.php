<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BaseControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Additional setup if needed

        // Create the testing database if it doesn't exist
        $this->createTestingDatabase();

        // Migrate the database
        $this->migrateDatabase();

        // Seed the database with necessary data
        $this->seedDatabase();
    }

    private function createTestingDatabase()
    {

        // Get the testing database name from .env.testing file
        $databaseName = env('DB_DATABASE', 'testing_db');


        // Create the testing database if it doesn't exist
        DB::statement("CREATE DATABASE IF NOT EXISTS $databaseName");
        DB::statement("GRANT CREATE, ALTER, DROP, INDEX, CREATE TEMPORARY TABLES, LOCK TABLES ON *.* TO 'admin'@'%'
        FLUSH PRIVILEGES");
        ;
    }

    private function migrateDatabase()
    {
        // Run database migrations
        $this->artisan('migrate');
    }

    private function seedDatabase()
    {
        // Seed the database with necessary data
        $this->artisan('db:seed');
    }

    public function testIndex()
    {
        // Prepare: Create and seed test data
        // Example: $this->seed(TestDataSeeder::class);

        // Act: Make a request to the index endpoint
        $response = $this->get('/api/endpoint'); // Replace with your endpoint URL

        // Assert: Verify the response and expected output
        $response->assertStatus(200); // Example: Assert a successful response
        // $response->assertJson([...]); // Example: Assert the expected JSON response
    }

    public function testAddToUser_WithValidData()
    {
        // Prepare: Create and seed test data
        // Example: $this->seed(TestDataSeeder::class);

        // Mock the request data
        $requestData = [
            'sources' => [1, 2],
            'categories' => [3],
            'authors' => [4, 5]
        ];

        // Mock the Validator instance
        $validatorMock = Validator::make($requestData, [
            'sources' => ['nullable', 'array'],
            'sources.*' => ['gt:0', 'lte:5', 'integer'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['gt:0', 'lte:5', 'integer'],
            'authors' => ['nullable', 'array'],
            'authors.*' => ['gt:0', 'lte:5', 'integer'],
        ]);

        // Replace the controller instance with a mock
        $controllerMock = $this->getMockBuilder(BaseController::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Set up the expectations for the mock
        $controllerMock->expects($this->once())
            ->method('addToUser')
            ->with($this->equalTo($requestData))
            ->willReturn('success'); // Mock the expected return value

        // Bind the mock instance to the container
        $this->app->instance(BaseController::class, $controllerMock);

        // Act: Make a request to the addToUser endpoint with valid data
        $response = $this->post('/api/endpoint', $requestData); // Replace with your endpoint URL and request data

        // Assert: Verify the response and expected output
        $response->assertStatus(200); // Example: Assert a successful response
        $response->assertJson(['result' => 'success']); // Example: Assert the expected JSON response
    }

    public function testAddToUser_WithInvalidData()
    {
        // Prepare: Create and seed test data
        // Example: $this->seed(TestDataSeeder::class);

        // Mock the request data
        $requestData = [
            'sources' => [6], // Invalid value
            'categories' => [3],
            'authors' => [4, 5]
        ];

        // Mock the Validator instance
        $validatorMock = Validator::make($requestData, [
            'sources' => ['nullable', 'array'],
            'sources.*' => ['gt:0', 'lte:5', 'integer'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['gt:0', 'lte:5', 'integer'],
            'authors' => ['nullable', 'array'],
            'authors.*' => ['gt:0', 'lte:5', 'integer'],
        ]);

        $validatorMock->fails(); // Trigger validation failure

        // Replace the controller instance with a mock
        $controllerMock = $this->getMockBuilder(BaseController::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Set up the expectations for the mock
        $controllerMock->expects($this->never()) // Assert that the addToUser method should not be called
            ->method('addToUser');

        // Bind the mock Validator instance to the container
        $this->app->instance(Validator::class, $validatorMock);

        // Bind the mock controller instance to the container
        $this->app->instance(BaseController::class, $controllerMock);

        // Act: Make a request to the addToUser endpoint with invalid data
        $response = $this->post('/api/endpoint', $requestData); // Replace with your endpoint URL and request data

        // Assert: Verify the response and expected error message
        $response->assertStatus(400); // Example: Assert a validation error response
        $response->assertJsonValidationErrors(['sources']); // Example: Assert the expected validation error field
    }
}
