<?php

namespace App\Services;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

class GuardianService
{
    private $guardianApiClient;
    private $apiKey;
    public function __construct()
    {
        $baseUri = Config::get('services.guardian.base_uri');


        $this->guardianApiClient = new Client(
            [
            'base_uri' => $baseUri,
            // 'headers' => [
            //     'api-key' => $apiKey,
            // ],
            ]
        );
        $this->apiKey = Config::get('services.guardian.key');
    }


    public function searchArticles($request)
    {
        $keyword = $request->input('keyword');
        $from = $request->input('from');
        $to = $request->input('to');
        $category = $request->input('category');
        $source = $request->input('source');

        $from = $from ? Carbon::parse($from)->format('Y-m-d') : $from;
        $to = $to ? Carbon::parse($to)->format('Y-m-d') : $to;
        $query = [
            'api-key' => $this->apiKey,
            'q' => $keyword,
            'from-date' => $from,
            'to-date' => $to,
            'section' => $category,
            'source' => $source,
        ];
        try {
            $response = $this->guardianApiClient->get('search', ['query' => $query]);
            $articles = json_decode($response->getBody(), true)['response']['results'];

            $filteredArticles = collect($articles)->map(
                function ($article) {
                    $baseUrl = parse_url($article['webUrl'], PHP_URL_HOST);
                    $image_url = isset($article['fields']['thumbnail']) ? $article['fields']['thumbnail'] : null;

                    return [
                    'title' => $article['webTitle'],
                    'webUrl' => $article['webUrl'],
                    'postDate' => $article['webPublicationDate'],
                    'category' => $article['sectionName'],
                    'image_url' => $image_url,
                    'source' => null, // Set as desired source value
                    'excerptContent' => null, // Set as desired exceptcontent value
                    ];
                }
            );
            return $filteredArticles;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
