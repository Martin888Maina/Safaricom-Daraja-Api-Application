@extends('layouts.app')

@section('title', 'C2B Simulate')

@section('content')
<div class="page-header">
    <h1>C2B Simulate</h1>
    <p>Simulate a customer sending money to a business shortcode via Paybill or Buy Goods. Used in sandbox to test C2B payment flows without a real phone.</p>
</div>

<div class="card">
    <div class="card-title">Simulate Customer-to-Business Payment</div>

    @if($errors->any())
        <ul class="error-list">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('c2b.submit') }}">
        @csrf

        <div class="form-group">
            <label for="phone">Customer Phone Number</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone', '254708374149') }}" required>
            <p class="hint">Format: 2547XXXXXXXX</p>
        </div>

        <div class="form-group">
            <label for="amount">Amount (KES)</label>
            <input type="number" id="amount" name="amount" value="{{ old('amount', 100) }}" min="1" required>
        </div>

        <div class="form-group">
            <label for="bill_ref">Bill Reference Number</label>
            <input type="text" id="bill_ref" name="bill_ref" value="{{ old('bill_ref', 'TestRef001') }}" maxlength="20" required>
            <p class="hint">The account number the customer is paying for. Max 20 characters.</p>
        </div>

        <button type="submit" class="btn btn-primary">Simulate C2B Payment</button>
    </form>

    @include('partials.response-card')

    <div class="how-it-works">
        <strong>How C2B Simulate works</strong>
        In production, a customer dials *150*00# and pays your shortcode (<strong>174379</strong>).
        In sandbox, this endpoint mimics that action — Daraja records the payment and fires a confirmation callback to your registered URL.
        A <strong>ResponseCode 0</strong> confirms the simulation was accepted.
    </div>
</div>
@endsection
