@extends('layouts.app')

@section('title', 'Edit Transaction #' . $transaction->id)

@section('content')

<div class="page-header">
    <h1>Edit Transaction #{{ $transaction->id }}</h1>
    <p>Update the status, result, or notes on this transaction record.</p>
</div>

<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('transactions.show', $transaction) }}" style="font-size: 0.855rem; color: var(--text-muted); text-decoration: none;">&larr; Back to Transaction</a>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; align-items: start;">

    {{-- Edit form --}}
    <div class="card" style="margin-bottom: 0;">
        <div class="card-title">Edit Fields</div>

        @if($errors->any())
            <ul class="error-list">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('transactions.update', $transaction) }}">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    @foreach(['pending', 'success', 'failed'] as $s)
                        <option value="{{ $s }}" {{ old('status', $transaction->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $transaction->phone_number) }}">
            </div>

            <div class="form-group">
                <label for="amount">Amount (KES)</label>
                <input type="number" id="amount" name="amount" value="{{ old('amount', $transaction->amount) }}" min="0" step="0.01">
            </div>

            <div class="form-group">
                <label for="result_code">Result Code</label>
                <input type="text" id="result_code" name="result_code" value="{{ old('result_code', $transaction->result_code) }}" maxlength="10">
                <p class="hint">Leave blank if not yet known.</p>
            </div>

            <div class="form-group">
                <label for="result_desc">Result Description</label>
                <input type="text" id="result_desc" name="result_desc" value="{{ old('result_desc', $transaction->result_desc) }}" maxlength="500">
            </div>

            <div style="display: flex; gap: 0.75rem; align-items: center; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('history') }}" style="font-size: 0.855rem; color: var(--text-muted); text-decoration: none;">Cancel</a>
            </div>
        </form>
    </div>

    {{-- Read-only summary --}}
    <div class="card" style="margin-bottom: 0;">
        <div class="card-title">Read-only Fields</div>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @foreach([
                ['API Type',    $transaction->api_type],
                ['Created',     $transaction->created_at->format('d M Y, H:i:s')],
                ['Checkout ID', $transaction->checkout_request_id ?? '—'],
                ['Merchant ID', $transaction->merchant_request_id ?? '—'],
                ['Originator',  $transaction->originator_conversation_id ?? '—'],
            ] as [$label, $value])
            <div>
                <p style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: var(--text-muted); margin-bottom: 0.2rem;">{{ $label }}</p>
                <p style="font-family: 'JetBrains Mono', monospace; font-size: 0.82rem; color: var(--text); word-break: break-all;">{{ $value }}</p>
            </div>
            @endforeach
        </div>
    </div>

</div>

@endsection
