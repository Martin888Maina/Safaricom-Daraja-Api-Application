@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- Hero --}}
<div class="card" style="background: linear-gradient(135deg, var(--green-primary) 0%, var(--green-dark) 100%); color: #fff; text-align: center; padding: 3rem 2rem;">
    <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: 0.75rem; color: #fff;">
        M-Pesa Daraja API Integration Demo
    </h1>
    <p style="font-size: 1.05rem; opacity: 0.9; max-width: 620px; margin: 0 auto 1.5rem;">
        A portfolio project demonstrating real-world integration with Safaricom's Daraja API —
        STK Push, STK Query, C2B and B2C payment flows running on the sandbox environment.
    </p>
    <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap;">
        <span style="background: rgba(255,255,255,0.2); padding: 0.35rem 0.9rem; border-radius: 20px; font-size: 0.82rem; font-weight: 600;">Laravel 13</span>
        <span style="background: rgba(255,255,255,0.2); padding: 0.35rem 0.9rem; border-radius: 20px; font-size: 0.82rem; font-weight: 600;">PHP 8.3</span>
        <span style="background: rgba(255,255,255,0.2); padding: 0.35rem 0.9rem; border-radius: 20px; font-size: 0.82rem; font-weight: 600;">Daraja Sandbox</span>
        <span style="background: rgba(255,255,255,0.2); padding: 0.35rem 0.9rem; border-radius: 20px; font-size: 0.82rem; font-weight: 600;">Pure CSS</span>
        <span style="background: rgba(255,255,255,0.2); padding: 0.35rem 0.9rem; border-radius: 20px; font-size: 0.82rem; font-weight: 600;">MySQL</span>
    </div>
</div>

{{-- API Cards --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem;">

    <div class="card" style="margin-bottom: 0; border-top: 4px solid var(--green-primary);">
        <div style="font-size: 2rem; margin-bottom: 0.75rem;">📲</div>
        <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--green-dark); margin-bottom: 0.5rem;">STK Push</h3>
        <p style="font-size: 0.875rem; color: #666; margin-bottom: 1.25rem; line-height: 1.55;">
            Trigger a payment prompt on a customer's phone. They enter their M-Pesa PIN to pay.
        </p>
        <a href="{{ route('stk-push.form') }}" class="btn btn-primary" style="font-size: 0.85rem; padding: 0.55rem 1.25rem;">Try It</a>
    </div>

    <div class="card" style="margin-bottom: 0; border-top: 4px solid var(--green-primary);">
        <div style="font-size: 2rem; margin-bottom: 0.75rem;">🔍</div>
        <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--green-dark); margin-bottom: 0.5rem;">STK Query</h3>
        <p style="font-size: 0.875rem; color: #666; margin-bottom: 1.25rem; line-height: 1.55;">
            Check the status of a previous STK Push using its Checkout Request ID.
        </p>
        <a href="{{ route('stk-query.form') }}" class="btn btn-primary" style="font-size: 0.85rem; padding: 0.55rem 1.25rem;">Try It</a>
    </div>

    <div class="card" style="margin-bottom: 0; border-top: 4px solid var(--green-primary);">
        <div style="font-size: 2rem; margin-bottom: 0.75rem;">💳</div>
        <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--green-dark); margin-bottom: 0.5rem;">C2B Simulate</h3>
        <p style="font-size: 0.875rem; color: #666; margin-bottom: 1.25rem; line-height: 1.55;">
            Simulate a customer paying a business shortcode via Paybill or Buy Goods.
        </p>
        <a href="{{ route('c2b.form') }}" class="btn btn-primary" style="font-size: 0.85rem; padding: 0.55rem 1.25rem;">Try It</a>
    </div>

    <div class="card" style="margin-bottom: 0; border-top: 4px solid var(--green-primary);">
        <div style="font-size: 2rem; margin-bottom: 0.75rem;">💸</div>
        <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--green-dark); margin-bottom: 0.5rem;">B2C Payment</h3>
        <p style="font-size: 0.875rem; color: #666; margin-bottom: 1.25rem; line-height: 1.55;">
            Send money from a business to a customer's M-Pesa wallet — salary, refunds, rewards.
        </p>
        <a href="{{ route('b2c.form') }}" class="btn btn-primary" style="font-size: 0.85rem; padding: 0.55rem 1.25rem;">Try It</a>
    </div>

</div>

{{-- Recent Transactions --}}
<div class="card">
    <div class="card-title" style="display: flex; justify-content: space-between; align-items: center;">
        <span>Recent Transactions</span>
        <a href="{{ route('history') }}" style="font-size: 0.82rem; color: var(--green-primary); text-decoration: none; font-weight: 600;">View all &rarr;</a>
    </div>

    @if($recent->isEmpty())
        <p style="color: #888; font-size: 0.9rem; text-align: center; padding: 2rem 0;">
            No transactions yet. Use one of the API forms above to get started.
        </p>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Date / Time</th>
                        <th>API Type</th>
                        <th>Phone</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recent as $tx)
                    <tr>
                        <td style="white-space: nowrap;">{{ $tx->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            <span style="font-family: monospace; font-size: 0.8rem; background: var(--green-light); padding: 0.15rem 0.5rem; border-radius: 4px;">
                                {{ $tx->api_type }}
                            </span>
                        </td>
                        <td>{{ $tx->phone_number ?? '—' }}</td>
                        <td>{{ $tx->amount ? 'KES ' . number_format($tx->amount, 2) : '—' }}</td>
                        <td>
                            <span class="badge badge-{{ $tx->status }}">{{ $tx->status }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Sandbox Info --}}
<div class="card" style="background: var(--green-light); border: 1.5px solid var(--green-primary);">
    <div class="card-title" style="border-bottom-color: rgba(0,153,0,0.25);">Sandbox Reference</div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; font-size: 0.875rem;">
        <div>
            <strong style="color: var(--green-dark);">Shortcode</strong>
            <p style="font-family: monospace; margin-top: 0.25rem;">174379</p>
        </div>
        <div>
            <strong style="color: var(--green-dark);">Test Phone</strong>
            <p style="font-family: monospace; margin-top: 0.25rem;">254708374149</p>
        </div>
        <div>
            <strong style="color: var(--green-dark);">Environment</strong>
            <p style="margin-top: 0.25rem;">Daraja Sandbox</p>
        </div>
        <div>
            <strong style="color: var(--green-dark);">Base URL</strong>
            <p style="font-family: monospace; font-size: 0.78rem; margin-top: 0.25rem;">sandbox.safaricom.co.ke</p>
        </div>
    </div>
</div>

@endsection
