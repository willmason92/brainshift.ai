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
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

//    'azureadb2c' => [
//        'client_id' => env('ADB2C_CLIENTID'),
//        'client_secret' => env('ADB2C_CLIENTSECRET'),
//        'redirect' => env('ADB2C_REDIRECTURI'),
//        'domain' => env('ADB2C_TENANT'),
//        'policy' => env('ADB2C_POLICY'),
//        'scope' => env('ADB2C_SCOPE', 'openid'),
//        'salesorg' => env('ADB2C_SALESORG', '8001_10_00'),
//        'epi_site' => env('ADB2C_EPI_SITE', 'CedralSidings-EternitWorld'),
//        'response_type' => env('ADB2C_RESPONSE_TYPE', 'code'),
//        'response_mode' => env('ADB2C_RESPONSE_MODE', 'form_post'),
//        'logout_location' => env('ADB2C_LOGOUT_LOCATION'),
//        'application_host_name' => env('ADB2C_CALLBACK_DOMAIN'),
//        'definition' => env('ADB2C_DEFINITION'),
//    ],

    'sendgrid' => [
        'api_key' => env('SENDGRID_API_KEY'),
    ],
];
