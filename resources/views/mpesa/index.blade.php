@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- Hero --}}
<div class="card" style="background: var(--navy); border-color: transparent; padding: 2.5rem 2rem;">
    <p style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.4); margin-bottom: 0.6rem;">Portfolio Project</p>
    <h1 style="font-size: 1.75rem; font-weight: 700; color: #fff; letter-spacing: -0.02em; margin-bottom: 0.75rem; line-height: 1.25;">
        Safaricom Daraja API<br>Integration Demo
    </h1>
    <p style="font-size: 0.9rem; color: rgba(255,255,255,0.5); max-width: 520px; line-height: 1.65; margin-bottom: 1.5rem;">
        A sandbox application demonstrating STK Push, STK Query, C2B and B2C payment flows
        using the Safaricom Daraja REST API — built with Laravel and pure CSS.
    </p>
    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
        @foreach(['Laravel 13', 'PHP 8.3', 'MySQL', 'Daraja Sandbox', 'Pure CSS'] as $tag)
        <span style="font-size: 0.75rem; font-weight: 500; color: rgba(255,255,255,0.5); border: 1px solid rgba(255,255,255,0.12); padding: 0.2rem 0.65rem; border-radius: 4px; font-family: 'JetBrains Mono', monospace;">{{ $tag }}</span>
        @endforeach
    </div>
</div>

{{-- API Cards --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 1rem; margin-bottom: 1.25rem;">

    @php
    $apis = [
        ['label' => 'STK Push',     'route' => 'stk-push.form',  'desc' => 'Trigger a payment prompt on a customer\'s phone via M-Pesa Express.'],
        ['label' => 'STK Query',    'route' => 'stk-query.form', 'desc' => 'Check the status of a previous STK Push by Checkout Request ID.'],
        ['label' => 'C2B Simulate', 'route' => 'c2b.form',       'desc' => 'Simulate a customer paying a business shortcode via Paybill.'],
        ['label' => 'B2C Payment',  'route' => 'b2c.form',       'desc' => 'Send money from a business to a customer\'s M-Pesa wallet.'],
    ];
    @endphp

    @foreach($apis as $api)
    <div class="card" style="margin-bottom: 0; display: flex; flex-direction: column;">
        <p class="card-title" style="margin-bottom: 0.75rem;">{{ $api['label'] }}</p>
        <p style="font-size: 0.855rem; color: var(--text-muted); flex: 1; margin-bottom: 1.25rem; line-height: 1.6;">{{ $api['desc'] }}</p>
        <a href="{{ route($api['route']) }}" class="btn btn-primary" style="align-self: flex-start;">Open</a>
    </div>
    @endforeach

</div>

{{-- Recent Transactions --}}
<div class="card">
    <div class="card-title" style="display: flex; justify-content: space-between; align-items: center;">
        <span>Recent Transactions</span>
        <a href="{{ route('history') }}" style="font-size: 0.8rem; color: var(--accent); text-decoration: none; font-weight: 500; text-transform: none; letter-spacing: 0;">View all</a>
    </div>

    @if($recent->isEmpty())
        <p style="color: var(--text-muted); font-size: 0.875rem; padding: 1.5rem 0;">
            No transactions yet. Use one of the API forms above to make your first call.
        </p>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Date / Time</th>
                        <th>API</th>
                        <th>Phone</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recent as $tx)
                    <tr>
                        <td style="white-space: nowrap; color: var(--text-muted); font-size: 0.83rem;">{{ $tx->created_at->format('d M Y, H:i') }}</td>
                        <td><span class="mono-tag">{{ $tx->api_type }}</span></td>
                        <td style="font-family: 'JetBrains Mono', monospace; font-size: 0.83rem;">{{ $tx->phone_number ?? '—' }}</td>
                        <td style="font-variant-numeric: tabular-nums;">{{ $tx->amount ? 'KES ' . number_format($tx->amount, 2) : '—' }}</td>
                        <td><span class="badge badge-{{ $tx->status }}">{{ $tx->status }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Sandbox Reference --}}
<div class="card">
    <div class="card-title">Sandbox Reference</div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.25rem;">
        @foreach([
            ['Shortcode',    '174379'],
            ['Test Phone',   '254708374149'],
            ['Environment',  'Sandbox'],
            ['Base URL',     'sandbox.safaricom.co.ke'],
        ] as [$label, $value])
        <div>
            <p style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); margin-bottom: 0.3rem;">{{ $label }}</p>
            <p style="font-family: 'JetBrains Mono', monospace; font-size: 0.83rem; color: var(--text);">{{ $value }}</p>
        </div>
        @endforeach
    </div>
</div>

@endsection
