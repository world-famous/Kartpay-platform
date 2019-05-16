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

    'sendgrid' => [
        'api_key' => env('SENDGRID_API_KEY', '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'),
        'version' => 'v3',
    ],

    'sucuri' => [
        'key'    => env('SUCURI_API_KEY', '34e5004f177a52c62e101da21fe780e5'),
        'secret' => env('SUCURI_API_SECRET', '9ec28c2676957e88337b15cda8072640')
    ],

];
