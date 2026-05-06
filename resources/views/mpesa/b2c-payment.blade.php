@extends('layouts.app')

@section('title', 'B2C Payment')

@section('content')
<div class="page-header">
    <h1>B2C Payment</h1>
    <p>Simulate a business sending money directly to a customer's M-Pesa wallet. Used for salary disbursements, refunds, and promotions.</p>
</div>

<div class="card">
    <div class="card-title">Initiate Business-to-Customer Payment</div>

    @if($errors->any())
        <ul class="error-list">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('b2c.submit') }}">
        @csrf

        <div class="form-group">
            <label for="phone">Recipient Phone Number</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone', '254708374149') }}" required>
            <p class="hint">Format: 2547XXXXXXXX</p>
        </div>

        <div class="form-group">
            <label for="amount">Amount (KES)</label>
            <input type="number" id="amount" name="amount" value="{{ old('amount', 10) }}" min="1" required>
        </div>

        <div class="form-group">
            <label for="command_id">Payment Type</label>
            <select id="command_id" name="command_id" required>
                <option value="SalaryPayment"    {{ old('command_id') === 'SalaryPayment'    ? 'selected' : '' }}>Salary Payment</option>
                <option value="BusinessPayment"  {{ old('command_id') === 'BusinessPayment'  ? 'selected' : '' }}>Business Payment</option>
                <option value="PromotionPayment" {{ old('command_id') === 'PromotionPayment' ? 'selected' : '' }}>Promotion Payment</option>
            </select>
        </div>

        <div class="form-group">
            <label for="remarks">Remarks</label>
            <input type="text" id="remarks" name="remarks" value="{{ old('remarks', 'Portfolio demo payment') }}" maxlength="100" required>
        </div>

        <button type="submit" class="btn btn-primary">Send B2C Payment</button>
    </form>

    @include('partials.response-card')

    <div class="how-it-works">
        <strong>How B2C Payment works</strong>
        The business initiator (shortcode <strong>174379</strong>) sends funds directly to a recipient's M-Pesa number.
        Daraja validates the request and sends an asynchronous result to your <em>ResultURL</em>.
        Common use cases: payroll disbursement, customer refunds, and loyalty rewards.
        A <strong>ResponseCode 0</strong> means the request was accepted and is being processed.
    </div>
</div>
@endsection
