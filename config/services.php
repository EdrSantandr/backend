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
    'facebook' => [
        'client_id' => '553696248843038',
        'client_secret' => '8f89bf7a35908e9554ce6eb08577e910',
        'redirect' => 'https://backend.test/login/facebook/callback',
    ],
    'github' => [
        'client_id' => '091eb7969afa6b3a6eac',
        'client_secret' =>'03ee238c9f96bd2644e3263d1bb052d13e741c2f',
        'redirect' => 'https://backend.test/login/github/callback',
    ],


];
