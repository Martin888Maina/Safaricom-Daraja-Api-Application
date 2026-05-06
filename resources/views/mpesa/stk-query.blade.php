@extends('layouts.app')

@section('title', 'STK Query')

@section('content')
<div class="page-header">
    <h1>STK Query</h1>
    <p>Check the status of a previously initiated STK Push request using its Checkout Request ID.</p>
</div>

<div class="card">
    <div class="card-title">Query STK Push Status</div>

    @if($errors->any())
        <ul class="error-list">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('stk-query.submit') }}">
        @csrf

        <div class="form-group">
            <label for="checkout_request_id">Checkout Request ID</label>
            <input type="text" id="checkout_request_id" name="checkout_request_id" value="{{ old('checkout_request_id') }}" placeholder="ws_CO_XXXXXXXXXXXXXXXXX" required>
            <p class="hint">Paste the <strong>CheckoutRequestID</strong> returned from the STK Push response.</p>
        </div>

        <button type="submit" class="btn btn-primary">Query Status</button>
    </form>

    @include('partials.response-card')

    <div class="how-it-works">
        <strong>How STK Query works</strong>
        After initiating an STK Push you receive a <em>CheckoutRequestID</em>. Use this endpoint to poll Daraja
        for the payment outcome — useful when the callback has not yet arrived or needs manual verification.
        A <strong>ResultCode 0</strong> means the payment was completed successfully.
    </div>
</div>
@endsection
