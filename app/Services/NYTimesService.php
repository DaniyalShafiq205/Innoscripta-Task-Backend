<?php

namespace App\Services;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

class NYTimesService
{
    private $nytApiClient;
    private $apiKey;
    public function __construct()
    {
        $baseUri = Config::get('services.nyt.base_uri');

        $this->nytApiClient = new Client(
            [
            'base_uri' => $baseUri,
            ]
        );
        $this->apiKey = Config::get('services.nyt.key');
    }

    public function searchArticles($request)
    {

        $keyword = $request->input('keyword');
        $from = $request->input('from');
        $to = $request->input('to');
        $category = $request->input('category');

        $page = $request->input('page');

        $from = $from ? Carbon::parse($from)->format('Y-m-d') : $from;
        $to = $to ? Carbon::parse($to)->format('Y-m-d') : $to;
        $query = [
            'api-key' => $this->apiKey,
            'q' => $keyword,
            'begin_date' => $from,
            'end_date' => $to,
            'page' => $page,
            'sort' => 'newest'
        ];

        if ($category) {
            $query['fq'] = "section_name:$category";
        }


        try {
            $response = $this->nytApiClient->get(
                'articlesearch.json',
                [
                'query' => $query,
                ]
            );

            $articles = json_decode($response->getBody(), true)['response']['docs'];

            $filteredArticles = collect($articles)->map(
                function ($article) {
                    $baseUrl = 'https://' . parse_url($article['web_url'], PHP_URL_HOST);
                    $firstMultimedia = array_shift($article['multimedia']);
                    $image_url = null;
                    if (isset($firstMultimedia['url'])) {
                        $image_url = $baseUrl . '/' . $firstMultimedia['url'];
                    }
                    return [
                    'title' => $article['headline']['main'],
                    'webUrl' => $article['web_url'],
                    'postDate' => $article['pub_date'],
                    'category' => $article['section_name'],
                    'image_url' =>  $image_url,
                    'source' => $article['source'],
                    'excerptContent' => $article['lead_paragraph']
                    ];
                }
            );

            return $filteredArticles;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
