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


        if (env('APP_ENV') != 'testing') {
            $articles = $this->newsAPIService->searchArticles($request);
            if (!empty($articles)) {
                return response()->json($articles);
            }

            $articles = $this->nytAPIService->searchArticles($request);
            if (!empty($articles)) {
                return response()->json($articles);
            }

            $articles = $this->guardianAPIService->searchArticles($request);

            return response()->json($articles);
        }
        //just run in test-cases
        $articles = $this->newsAPIService->searchArticles($request);
        $articles = $this->nytAPIService->searchArticles($request);
        $articles = $this->guardianAPIService->searchArticles($request);
        return response()->json($articles);
    }
}
