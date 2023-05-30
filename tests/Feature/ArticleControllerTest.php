<?php

namespace Tests\Feature;

use Mockery;
use App\Models\User;
use Illuminate\Http\Request;
use Tests\TestCase;
use App\Services\NewsAPIService;
use App\Services\NYTimesService;
use App\Services\GuardianService;
use Illuminate\Validation\ValidationRule;
use App\Http\Controllers\ArticleController;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase; // Optional, if you need to refresh the database for your tests

    public function testInvokeReturnsArticlesFromNewsAPI()
    {


        $credentials = [
            'email' => 'admin@gmail.com',
            'password' => 'admin',
        ];
        $response = $this->postJson('/api/login', $credentials);


            $response = $this->withHeaders(['Authorization' => 'Bearer ' . $response['token']])
                ->getJson('/api/articles?keyword=Testing&from=2023-05-01&to=2023-05-10&page=2&api=news');

        // Assert the response status code
        $response->assertStatus(200);
    
    }

    public function testInvokeReturnsArticlesFromNYTAPI()
    {


        $credentials = [
            'email' => 'admin@gmail.com',
            'password' => 'admin',
        ];
        $response = $this->postJson('/api/login', $credentials);


            $response = $this->withHeaders(['Authorization' => 'Bearer ' . $response['token']])
                ->getJson('/api/articles?keyword=Testing&from=2023-05-01&to=2023-05-10&page=2&api=nyt');

        // Assert the response status code
        $response->assertStatus(200);
    
    }


    public function testInvokeReturnsArticlesFromGuardianAPI()
    {


        $credentials = [
            'email' => 'admin@gmail.com',
            'password' => 'admin',
        ];
        $response = $this->postJson('/api/login', $credentials);


            $response = $this->withHeaders(['Authorization' => 'Bearer ' . $response['token']])
                ->getJson('/api/articles?keyword=Testing&from=2023-05-01&to=2023-05-10&page=2&api=guardian');

        // Assert the response status code
        $response->assertStatus(200);
    
    }

}
