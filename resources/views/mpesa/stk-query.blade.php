@extends('layouts.app')

@section('title', 'STK Query')

@section('content')
<div class="page-header">
    <h1>STK Query</h1>
    <p>Check the status of a previously initiated STK Push using its Checkout Request ID.</p>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; align-items: start;">

    {{-- Query form --}}
    <div class="card" style="margin-bottom: 0;">
        <div class="card-title">Query STK Push Status</div>

        @if($errors->any())
            <ul class="error-list">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('stk-query.submit') }}" id="query-form">
            @csrf

            <div class="form-group">
                <label for="checkout_request_id">Checkout Request ID</label>
                <input
                    type="text"
                    id="checkout_request_id"
                    name="checkout_request_id"
                    value="{{ old('checkout_request_id') }}"
                    placeholder="Select from recent pushes or paste manually"
                    required
                >
                <p class="hint">Select a recent STK Push from the panel on the right, or paste a Checkout Request ID manually.</p>
            </div>

            <button type="submit" class="btn btn-primary">Query Status</button>
        </form>

        @include('partials.response-card')

        <div class="how-it-works">
            <strong>How STK Query works</strong>
            After initiating an STK Push, Daraja returns a <em>CheckoutRequestID</em>.
            Use this endpoint to poll for the payment outcome — useful when the callback has not yet arrived or needs manual verification.
            A <strong>ResultCode 0</strong> means the payment completed successfully.
        </div>
    </div>

    {{-- Recent STK Pushes panel --}}
    <div class="card" style="margin-bottom: 0;">
        <div class="card-title">Recent STK Pushes</div>

        @if($recentPushes->isEmpty())
            <p style="color: var(--text-muted); font-size: 0.875rem; padding: 1rem 0;">
                No STK Push transactions found. Go to <a href="{{ route('stk-push.form') }}" style="color: var(--accent);">STK Push</a> and make a request first.
            </p>
        @else
            <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 1rem;">
                Click a row to load its Checkout Request ID into the form.
            </p>
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                @foreach($recentPushes as $push)
                <div
                    onclick="selectCheckoutId('{{ $push->checkout_request_id }}')"
                    style="padding: 0.75rem 1rem; border: 1px solid var(--border); border-radius: var(--radius); cursor: pointer; transition: background 0.15s, border-color 0.15s;"
                    onmouseover="this.style.background='var(--bg)'; this.style.borderColor='var(--accent)';"
                    onmouseout="this.style.background=''; this.style.borderColor='var(--border)';"
                >
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                        <span style="font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; color: var(--text); word-break: break-all;">{{ $push->checkout_request_id }}</span>
                        <span class="badge badge-{{ $push->status }}" style="margin-left: 0.5rem; flex-shrink: 0;">{{ $push->status }}</span>
                    </div>
                    <div style="display: flex; gap: 1rem; font-size: 0.78rem; color: var(--text-muted);">
                        <span>{{ $push->phone_number ?? '—' }}</span>
                        <span>{{ $push->amount ? 'KES ' . number_format($push->amount, 2) : '—' }}</span>
                        <span>{{ $push->created_at->format('d M, H:i') }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

@push('styles')
<script>
    function selectCheckoutId(id) {
        document.getElementById('checkout_request_id').value = id;
        document.getElementById('checkout_request_id').focus();
    }
</script>
@endpush

@endsection
