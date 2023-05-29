<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\NewsAPIService;
use App\Services\NYTimesService;
use App\Services\GuardianService;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    private $newsApiClient;

    // public function __construct()
    // {
    //     $this->newsApiClient = new Client([
    //         'base_uri' => 'https://newsapi.org/v2/',
    //     ]);
    // }

    private $newsAPIService;
    private $nytAPIService;
    private $guardianAPIService;

    public function __construct(NewsAPIService $newsAPIService, NYTimesService $nytAPIService, GuardianService $guardianAPIService)
    {
        $this->newsAPIService = $newsAPIService;
        $this->nytAPIService = $nytAPIService;
        $this->guardianAPIService = $guardianAPIService;
    }

    public function __invoke(Request $request)
    {


        $rules = [
            'api' => [
                'required',
                Rule::in(['news', 'nyt', 'guardian']),
            ],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        switch ($request->api) {
            case 'news':
                $articles = $this->newsAPIService->searchArticles($request);
                // Process and return the response
                return response()->json($articles);
                break;

            case 'nyt':
                $articles = $this->nytAPIService->searchArticles($request);
                // Process and return the response
                return response()->json($articles);
                break;

            case 'guardian':
                $articles = $this->guardianAPIService->searchArticles($request);
                // Process and return the response
                return response()->json($articles);
                break;

            default:
                throw new \Exception('Something went wrong');
                break;
        }
    }
}
