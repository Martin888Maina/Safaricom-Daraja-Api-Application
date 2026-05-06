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
        $recentPushes = MpesaTransaction::where('api_type', 'stk_push')
            ->whereNotNull('checkout_request_id')
            ->latest()
            ->limit(10)
            ->get(['id', 'checkout_request_id', 'phone_number', 'amount', 'status', 'created_at']);

        return view('mpesa.stk-query', compact('recentPushes'));
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

    public function history(Request $request)
    {
        $query = MpesaTransaction::latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('phone_number', 'like', "%{$search}%")
                  ->orWhere('api_type', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhere('checkout_request_id', 'like', "%{$search}%")
                  ->orWhere('result_code', 'like', "%{$search}%");
            });
        }

        $transactions = $query->paginate(10)->withQueryString();

        return view('mpesa.history', compact('transactions'));
    }

    public function show(MpesaTransaction $transaction)
    {
        return view('mpesa.show', compact('transaction'));
    }

    public function edit(MpesaTransaction $transaction)
    {
        return view('mpesa.edit', compact('transaction'));
    }

    public function update(Request $request, MpesaTransaction $transaction)
    {
        $data = $request->validate([
            'status'      => ['required', 'in:pending,success,failed'],
            'result_code' => ['nullable', 'string', 'max:10'],
            'result_desc' => ['nullable', 'string', 'max:500'],
            'phone_number'=> ['nullable', 'string', 'max:20'],
            'amount'      => ['nullable', 'numeric', 'min:0'],
        ]);

        $transaction->update($data);

        return redirect()->route('history')->with('flash_success', "Transaction #{$transaction->id} updated successfully.");
    }

    public function destroy(MpesaTransaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('history')->with('flash_success', "Transaction #{$transaction->id} deleted.");
    }
}
