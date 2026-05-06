<?php

namespace App\Http\Controllers;

use App\Models\MpesaTransaction;
use App\Services\MpesaService;
use Illuminate\Http\Request;

class MpesaController extends Controller
{
    public function __construct(private MpesaService $mpesa) {}

    public function index()
    {
        $recent = MpesaTransaction::latest()->limit(5)->get();
        return view('mpesa.index', compact('recent'));
    }

    public function stkPushForm()
    {
        return view('mpesa.stk-push');
    }

    public function stkPush(Request $request)
    {
        $data = $request->validate([
            'phone'       => ['required', 'string', 'regex:/^2547\d{8}$/'],
            'amount'      => ['required', 'integer', 'min:1'],
            'account_ref' => ['required', 'string', 'max:12'],
            'description' => ['required', 'string', 'max:13'],
        ]);

        $response = $this->mpesa->stkPush(
            $data['phone'],
            (int) $data['amount'],
            $data['account_ref'],
            $data['description']
        );

        MpesaTransaction::create([
            'api_type'            => 'stk_push',
            'phone_number'        => $data['phone'],
            'amount'              => $data['amount'],
            'request_payload'     => $data,
            'response_payload'    => $response,
            'merchant_request_id' => $response['MerchantRequestID'] ?? null,
            'checkout_request_id' => $response['CheckoutRequestID'] ?? null,
            'status'              => isset($response['ResponseCode']) && $response['ResponseCode'] === '0' ? 'pending' : 'failed',
        ]);

        return back()->with('mpesa_response', $response);
    }

    public function stkQueryForm()
    {
        return view('mpesa.stk-query');
    }

    public function stkQuery(Request $request)
    {
        $data = $request->validate([
            'checkout_request_id' => ['required', 'string'],
        ]);

        $response = $this->mpesa->stkQuery($data['checkout_request_id']);

        MpesaTransaction::create([
            'api_type'            => 'stk_query',
            'request_payload'     => $data,
            'response_payload'    => $response,
            'checkout_request_id' => $data['checkout_request_id'],
            'result_code'         => $response['ResultCode'] ?? null,
            'result_desc'         => $response['ResultDesc'] ?? null,
            'status'              => isset($response['ResultCode']) && $response['ResultCode'] === '0' ? 'success' : 'failed',
        ]);

        return back()->with('mpesa_response', $response);
    }

    public function c2bForm()
    {
        return view('mpesa.c2b-simulate');
    }

    public function c2bSimulate(Request $request)
    {
        $data = $request->validate([
            'phone'    => ['required', 'string', 'regex:/^2547\d{8}$/'],
            'amount'   => ['required', 'integer', 'min:1'],
            'bill_ref' => ['required', 'string', 'max:20'],
        ]);

        $response = $this->mpesa->c2bSimulate(
            $data['phone'],
            (int) $data['amount'],
            $data['bill_ref']
        );

        MpesaTransaction::create([
            'api_type'         => 'c2b_simulate',
            'phone_number'     => $data['phone'],
            'amount'           => $data['amount'],
            'request_payload'  => $data,
            'response_payload' => $response,
            'result_code'      => $response['ResponseCode'] ?? null,
            'result_desc'      => $response['ResponseDescription'] ?? null,
            'status'           => isset($response['ResponseCode']) && $response['ResponseCode'] === '0' ? 'success' : 'failed',
        ]);

        return back()->with('mpesa_response', $response);
    }

    public function b2cForm()
    {
        return view('mpesa.b2c-payment');
    }

    public function b2cPayment(Request $request)
    {
        $data = $request->validate([
            'phone'      => ['required', 'string', 'regex:/^2547\d{8}$/'],
            'amount'     => ['required', 'integer', 'min:1'],
            'command_id' => ['required', 'in:SalaryPayment,BusinessPayment,PromotionPayment'],
            'remarks'    => ['required', 'string', 'max:100'],
        ]);

        $response = $this->mpesa->b2cPayment(
            $data['phone'],
            (int) $data['amount'],
            $data['command_id'],
            $data['remarks']
        );

        MpesaTransaction::create([
            'api_type'                   => 'b2c_payment',
            'phone_number'               => $data['phone'],
            'amount'                     => $data['amount'],
            'request_payload'            => $data,
            'response_payload'           => $response,
            'originator_conversation_id' => $response['OriginatorConversationID'] ?? null,
            'result_code'                => $response['ResponseCode'] ?? null,
            'result_desc'                => $response['ResponseDescription'] ?? null,
            'status'                     => isset($response['ResponseCode']) && $response['ResponseCode'] === '0' ? 'pending' : 'failed',
        ]);

        return back()->with('mpesa_response', $response);
    }

    public function history()
    {
        $transactions = MpesaTransaction::latest()->paginate(15);
        return view('mpesa.history', compact('transactions'));
    }
}
