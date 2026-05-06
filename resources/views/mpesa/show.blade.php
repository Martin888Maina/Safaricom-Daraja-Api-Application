@extends('layouts.app')

@section('title', 'Transaction #' . $transaction->id)

@section('content')

<div class="page-header">
    <h1>Transaction #{{ $transaction->id }}</h1>
    <p>Full details for this Daraja API call.</p>
</div>

<div style="display: flex; gap: 0.75rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
    <a href="{{ route('history') }}" style="font-size: 0.855rem; color: var(--text-muted); text-decoration: none;">&larr; Back to History</a>
    <span style="color: var(--border);">|</span>
    <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-primary" style="padding: 0.35rem 0.9rem; font-size: 0.855rem;">Edit</a>
</div>

{{-- Summary --}}
<div class="card">
    <div class="card-title">Summary</div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.25rem;">
        @foreach([
            ['ID',         '#' . $transaction->id],
            ['API Type',   $transaction->api_type],
            ['Phone',      $transaction->phone_number ?? '—'],
            ['Amount',     $transaction->amount ? 'KES ' . number_format($transaction->amount, 2) : '—'],
            ['Status',     $transaction->status],
            ['Result Code',$transaction->result_code ?? '—'],
            ['Created',    $transaction->created_at->format('d M Y, H:i:s')],
            ['Updated',    $transaction->updated_at->format('d M Y, H:i:s')],
        ] as [$label, $value])
        <div>
            <p style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: var(--text-muted); margin-bottom: 0.3rem;">{{ $label }}</p>
            @if($label === 'Status')
                <span class="badge badge-{{ $value }}">{{ $value }}</span>
            @else
                <p style="font-family: 'JetBrains Mono', monospace; font-size: 0.855rem; color: var(--text);">{{ $value }}</p>
            @endif
        </div>
        @endforeach
    </div>

    @if($transaction->result_desc)
        <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid var(--border);">
            <p style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: var(--text-muted); margin-bottom: 0.3rem;">Result Description</p>
            <p style="font-size: 0.875rem; color: var(--text);">{{ $transaction->result_desc }}</p>
        </div>
    @endif

    @if($transaction->checkout_request_id)
        <div style="margin-top: 1rem;">
            <p style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: var(--text-muted); margin-bottom: 0.3rem;">Checkout Request ID</p>
            <p style="font-family: 'JetBrains Mono', monospace; font-size: 0.82rem; color: var(--text);">{{ $transaction->checkout_request_id }}</p>
        </div>
    @endif

    @if($transaction->merchant_request_id)
        <div style="margin-top: 1rem;">
            <p style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: var(--text-muted); margin-bottom: 0.3rem;">Merchant Request ID</p>
            <p style="font-family: 'JetBrains Mono', monospace; font-size: 0.82rem; color: var(--text);">{{ $transaction->merchant_request_id }}</p>
        </div>
    @endif

    @if($transaction->originator_conversation_id)
        <div style="margin-top: 1rem;">
            <p style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: var(--text-muted); margin-bottom: 0.3rem;">Originator Conversation ID</p>
            <p style="font-family: 'JetBrains Mono', monospace; font-size: 0.82rem; color: var(--text);">{{ $transaction->originator_conversation_id }}</p>
        </div>
    @endif
</div>

{{-- Payloads --}}
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
    <div class="card" style="margin-bottom: 0;">
        <div class="card-title">Request Payload</div>
        <pre style="font-family: 'JetBrains Mono', monospace; font-size: 0.8rem; background: var(--bg); padding: 1rem; border-radius: var(--radius); border: 1px solid var(--border); overflow-x: auto; white-space: pre-wrap; word-break: break-all; line-height: 1.65;">{{ json_encode($transaction->request_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
    </div>
    <div class="card" style="margin-bottom: 0;">
        <div class="card-title">Response Payload</div>
        <pre style="font-family: 'JetBrains Mono', monospace; font-size: 0.8rem; background: var(--bg); padding: 1rem; border-radius: var(--radius); border: 1px solid var(--border); overflow-x: auto; white-space: pre-wrap; word-break: break-all; line-height: 1.65;">{{ json_encode($transaction->response_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
    </div>
</div>

@endsection
