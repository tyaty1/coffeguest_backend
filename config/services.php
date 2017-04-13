<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

 /*   'facebook' => [
        'client_id' => '1314213785326262',
        'client_secret' => 'cb5ef7018b3eac59976aa49ebde06920',
        'redirect' => env('APP_URL').env('FB_REDIRECT_URL'),
    ],*/

'facebook' => [
    'client_id' => env('FB_KEY'),
    'client_secret' => env('FB_SECRET'),
    'redirect' => 'http://cafe-rest.localhost/api/auth/facebook/login',
],
];
