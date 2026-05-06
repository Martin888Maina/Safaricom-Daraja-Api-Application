<?php

namespace App\Http\Controllers;

use App\Models\MpesaTransaction;
use Illuminate\Http\Request;

class CallbackController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        $body = $payload['Body']['stkCallback'] ?? $payload['Result'] ?? null;

        if ($body) {
            $checkoutId      = $body['CheckoutRequestID'] ?? null;
            $conversationId  = $body['OriginatorConversationID'] ?? null;
            $resultCode      = (string) ($body['ResultCode'] ?? '');
            $resultDesc      = $body['ResultDesc'] ?? '';
            $status          = $resultCode === '0' ? 'success' : 'failed';

            $transaction = null;

            if ($checkoutId) {
                $transaction = MpesaTransaction::where('checkout_request_id', $checkoutId)->first();
            }

            if (!$transaction && $conversationId) {
                $transaction = MpesaTransaction::where('originator_conversation_id', $conversationId)->first();
            }

            if ($transaction) {
                $transaction->update([
                    'result_code' => $resultCode,
                    'result_desc' => $resultDesc,
                    'status'      => $status,
                ]);
            }
        }

        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }
}
