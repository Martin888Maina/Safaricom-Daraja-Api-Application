<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MpesaTransaction extends Model
{
    protected $fillable = [
        'api_type',
        'phone_number',
        'amount',
        'request_payload',
        'response_payload',
        'merchant_request_id',
        'checkout_request_id',
        'originator_conversation_id',
        'result_code',
        'result_desc',
        'status',
    ];

    protected $casts = [
        'request_payload'  => 'array',
        'response_payload' => 'array',
    ];
}
