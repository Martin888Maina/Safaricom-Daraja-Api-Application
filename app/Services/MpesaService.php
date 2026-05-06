<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class MpesaService
{
    public function getAccessToken(): string
    {
        return Cache::remember('mpesa_access_token', 55 * 60, function () {
            $response = Http::withBasicAuth(
                config('mpesa.consumer_key'),
                config('mpesa.consumer_secret')
            )->get(config('mpesa.base_url') . '/oauth/v1/generate', [
                'grant_type' => 'client_credentials',
            ]);

            return $response->json('access_token');
        });
    }

    public function generatePassword(string $timestamp): string
    {
        return base64_encode(
            config('mpesa.shortcode') . config('mpesa.passkey') . $timestamp
        );
    }

    public function stkPush(string $phone, int $amount, string $accountRef, string $description): array
    {
        $timestamp = now()->format('YmdHis');

        return $this->post('/mpesa/stkpush/v1/processrequest', [
            'BusinessShortCode' => config('mpesa.shortcode'),
            'Password'          => $this->generatePassword($timestamp),
            'Timestamp'         => $timestamp,
            'TransactionType'   => 'CustomerPayBillOnline',
            'Amount'            => $amount,
            'PartyA'            => $phone,
            'PartyB'            => config('mpesa.shortcode'),
            'PhoneNumber'       => $phone,
            'CallBackURL'       => config('mpesa.callback_url'),
            'AccountReference'  => $accountRef,
            'TransactionDesc'   => $description,
        ]);
    }

    public function stkQuery(string $checkoutRequestId): array
    {
        $timestamp = now()->format('YmdHis');

        return $this->post('/mpesa/stkpushquery/v1/query', [
            'BusinessShortCode' => config('mpesa.shortcode'),
            'Password'          => $this->generatePassword($timestamp),
            'Timestamp'         => $timestamp,
            'CheckoutRequestID' => $checkoutRequestId,
        ]);
    }

    public function c2bSimulate(string $phone, int $amount, string $billRef): array
    {
        return $this->post('/mpesa/c2b/v1/simulate', [
            'ShortCode'     => config('mpesa.shortcode'),
            'CommandID'     => 'CustomerPayBillOnline',
            'Amount'        => $amount,
            'Msisdn'        => $phone,
            'BillRefNumber' => $billRef,
        ]);
    }

    public function b2cPayment(string $phone, int $amount, string $commandId, string $remarks): array
    {
        return $this->post('/mpesa/b2c/v1/paymentrequest', [
            'InitiatorName'      => config('mpesa.b2c_initiator_name'),
            'SecurityCredential' => config('mpesa.b2c_security_credential'),
            'CommandID'          => $commandId,
            'Amount'             => $amount,
            'PartyA'             => config('mpesa.shortcode'),
            'PartyB'             => $phone,
            'Remarks'            => $remarks,
            'QueueTimeOutURL'    => config('mpesa.queue_timeout_url'),
            'ResultURL'          => config('mpesa.result_url'),
            'Occasion'           => '',
        ]);
    }

    private function post(string $endpoint, array $payload): array
    {
        $response = Http::withToken($this->getAccessToken())
            ->post(config('mpesa.base_url') . $endpoint, $payload);

        return $response->json() ?? [];
    }
}
