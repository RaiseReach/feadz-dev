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
    'facebook' => [
        'client_id'     => '1907054672948033',
        'client_secret' => '33e4d90af95bd8942514d2b1ba8b73ce',
        'redirect'      => 'http://feadz.com/login/callback/facebook',
    ],
    'google' => [
        'client_id' => '250713834094-fugv7prj2cs1178f1587ch2nsosjb9ed.apps.googleusercontent.com',
        'client_secret' => 'PumiqG4vzatF1Qlp-J06ftDj',
        'redirect' => 'http://feadz.com/login/callback/google',
    ],
];
