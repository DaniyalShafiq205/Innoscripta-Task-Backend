<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'newsapi' => [
        'base_uri' => env('NEWS_API_BASE_URI', 'https://newsapi.org/v2/'),
        'key' => env('NEWS_API_KEY', '670f302f55954574a3933f68d6722c28'),
        'searchIn'=>'title,description'
        ,
        'sortBy' =>'publishedAt'
    ],

    'nyt' => [
        'base_uri' => env('NYT_API_BASE_URI', 'https://api.nytimes.com/svc/search/v2/'),
        'key' => env('NYT_API_KEY', 'lj9rZYI6eia0e7FIw8gV1zcJZgGGb3aC'),
    ],

    'guardian' => [
        'base_uri' => env('GUARDIAN_API_BASE_URI', 'https://content.guardianapis.com/search'),
        'key' => env('GUARDIAN_API_KEY', '7b8e9795-c02d-4b3f-bd84-df0ea1e6b516'),
    ],

];
