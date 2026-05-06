<?php

return [
    'env'                      => env('MPESA_ENV', 'sandbox'),
    'base_url'                 => env('MPESA_BASE_URL', 'https://sandbox.safaricom.co.ke'),
    'consumer_key'             => env('MPESA_CONSUMER_KEY'),
    'consumer_secret'          => env('MPESA_CONSUMER_SECRET'),
    'passkey'                  => env('MPESA_PASSKEY'),
    'shortcode'                => env('MPESA_SHORTCODE', '174379'),
    'callback_url'             => env('MPESA_CALLBACK_URL'),
    'b2c_initiator_name'       => env('MPESA_B2C_INITIATOR_NAME', 'testapi'),
    'b2c_security_credential'  => env('MPESA_B2C_SECURITY_CREDENTIAL'),
    'queue_timeout_url'        => env('MPESA_QUEUE_TIMEOUT_URL'),
    'result_url'               => env('MPESA_RESULT_URL'),
];
