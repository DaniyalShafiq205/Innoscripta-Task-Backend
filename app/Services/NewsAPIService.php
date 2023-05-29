<?php
namespace App\Services;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class NewsAPIService
{
    private $newsApiClient;
    private $apiKey;
    public function __construct()
    {
        $baseUri = Config::get('services.newsapi.base_uri');
        $this->newsApiClient = new Client([
            'base_uri' => $baseUri,
        ]);
        $this->apiKey = Config::get('services.newsapi.key');
    }

    public function searchArticles(Request $request)
    {

        $keyword = $request->input('keyword');
        $from = $request->input('from');
        $to = $request->input('to');
        $source = $request->input('source');
        $pageSize = $request->input('pageSize');
        $page = $request->input('page');

        $searchIn = Config::get('services.newsapi.searchIn');
        $sortBy = Config::get('services.newsapi.searchIn');

        $from = $from ? Carbon::parse($from)->format('Y-m-d') : $from;
        $to = $to ? Carbon::parse($to)->format('Y-m-d') : $to;
        $queryParams = [
            'q' => $keyword,
            'from' =>  $from,
            'to' => $to,
            'sortBy' => $sortBy,
            'source' => $source,
            'apiKey' =>$this->apiKey,
            'pageSize' => $pageSize,
            'page' => $page,
            'searchIn' =>  $searchIn ,
        ];

        try {
            $response = $this->newsApiClient->get('everything', [
                'query' => $queryParams,
            ]);
            $articles = json_decode($response->getBody()->getContents(), true)['articles'];


            $articleObjects = collect($articles)->map(function ($article) {
                return [
                    'title' => $article['title'],
                    'webUrl' => $article['url'],
                    'postDate' => $article['publishedAt'],
                    'category' => null,
                    'image_url' => $article['urlToImage'],
                    'source' => $article['source']['name'],
                    'excerptContent' => $article['content'],
                ];
            })->toArray();

            $articleObjects['page']=$page;
            return $articleObjects;
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage(),500);
        }
    }
}
