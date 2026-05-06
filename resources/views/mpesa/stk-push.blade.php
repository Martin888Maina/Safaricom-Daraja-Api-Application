@extends('layouts.app')

@section('title', 'STK Push')

@section('content')
<div class="page-header">
    <h1>STK Push</h1>
    <p>Trigger a payment prompt directly on a customer's phone. The customer enters their M-Pesa PIN to complete the transaction.</p>
</div>

<div class="card">
    <div class="card-title">Initiate STK Push — M-Pesa Express</div>

    @if($errors->any())
        <ul class="error-list">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('stk-push.submit') }}">
        @csrf

        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone', '254708374149') }}" required>
            <p class="hint">Format: 2547XXXXXXXX &nbsp;&mdash;&nbsp; Sandbox test number: 254708374149</p>
        </div>

        <div class="form-group">
            <label for="amount">Amount (KES)</label>
            <input type="number" id="amount" name="amount" value="{{ old('amount', 1) }}" min="1" required>
        </div>

        <div class="form-group">
            <label for="account_ref">Account Reference</label>
            <input type="text" id="account_ref" name="account_ref" value="{{ old('account_ref', 'Portfolio') }}" maxlength="12" required>
            <p class="hint">Max 12 characters. Appears on the customer's M-Pesa statement.</p>
        </div>

        <div class="form-group">
            <label for="description">Transaction Description</label>
            <input type="text" id="description" name="description" value="{{ old('description', 'Demo Payment') }}" maxlength="13" required>
            <p class="hint">Max 13 characters.</p>
        </div>

        <button type="submit" class="btn btn-primary">Send STK Push</button>
    </form>

    @include('partials.response-card')

    <div class="how-it-works">
        <strong>How STK Push works</strong>
        Daraja sends a payment prompt to the customer's phone via USSD. The customer enters their M-Pesa PIN to authorise the payment.
        Daraja then sends an asynchronous callback to your server with the final result (success or failure).
        In sandbox mode the phone <strong>254708374149</strong> simulates this flow without a real SIM card.
    </div>
</div>
@endsection
