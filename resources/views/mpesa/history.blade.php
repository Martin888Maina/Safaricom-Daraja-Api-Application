@extends('layouts.app')

@section('title', 'Transaction History')

@section('content')

<div class="page-header">
    <h1>Transaction History</h1>
    <p>A full log of every Daraja API call made through this application, including request payloads and Daraja responses.</p>
</div>

<div class="card">
    <div class="card-title" style="display: flex; justify-content: space-between; align-items: center;">
        <span>All Transactions</span>
        <span style="font-size: 0.82rem; color: #888; font-weight: 400;">{{ $transactions->total() }} total</span>
    </div>

    @if($transactions->isEmpty())
        <p style="color: #888; font-size: 0.9rem; text-align: center; padding: 3rem 0;">
            No transactions recorded yet. Use an API form to make your first call.
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
                        <th>Result</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $tx)
                    <tr>
                        <td style="white-space: nowrap; font-size: 0.82rem;">
                            {{ $tx->created_at->format('d M Y') }}<br>
                            <span style="color: #888;">{{ $tx->created_at->format('H:i:s') }}</span>
                        </td>
                        <td>
                            <span style="font-family: monospace; font-size: 0.78rem; background: var(--green-light); padding: 0.15rem 0.5rem; border-radius: 4px; white-space: nowrap;">
                                {{ $tx->api_type }}
                            </span>
                        </td>
                        <td style="font-family: monospace; font-size: 0.85rem;">{{ $tx->phone_number ?? '—' }}</td>
                        <td style="white-space: nowrap;">{{ $tx->amount ? 'KES ' . number_format($tx->amount, 2) : '—' }}</td>
                        <td><span class="badge badge-{{ $tx->status }}">{{ $tx->status }}</span></td>
                        <td style="font-size: 0.82rem; max-width: 180px;">
                            @if($tx->result_code !== null)
                                <span style="font-family: monospace; color: {{ $tx->result_code === '0' ? 'var(--green-dark)' : 'var(--red-error)' }};">
                                    Code: {{ $tx->result_code }}
                                </span><br>
                                <span style="color: #666;">{{ Str::limit($tx->result_desc, 40) }}</span>
                            @else
                                <span style="color: #aaa;">—</span>
                            @endif
                        </td>
                        <td>
                            <button
                                onclick="toggleDetails({{ $tx->id }})"
                                style="background: none; border: 1.5px solid var(--green-primary); color: var(--green-primary); padding: 0.3rem 0.7rem; border-radius: var(--radius); font-size: 0.78rem; cursor: pointer; font-weight: 600;"
                            >
                                JSON
                            </button>
                        </td>
                    </tr>
                    {{-- Expandable JSON row --}}
                    <tr id="details-{{ $tx->id }}" style="display: none;">
                        <td colspan="7" style="background: var(--gray-100); padding: 1rem 1.25rem;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                <div>
                                    <strong style="font-size: 0.78rem; color: var(--green-dark); display: block; margin-bottom: 0.4rem;">REQUEST PAYLOAD</strong>
                                    <pre style="font-size: 0.75rem; background: #fff; padding: 0.75rem; border-radius: var(--radius); border: 1px solid var(--gray-200); overflow-x: auto; white-space: pre-wrap; word-break: break-all;">{{ json_encode($tx->request_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                                <div>
                                    <strong style="font-size: 0.78rem; color: var(--green-dark); display: block; margin-bottom: 0.4rem;">RESPONSE PAYLOAD</strong>
                                    <pre style="font-size: 0.75rem; background: #fff; padding: 0.75rem; border-radius: var(--radius); border: 1px solid var(--gray-200); overflow-x: auto; white-space: pre-wrap; word-break: break-all;">{{ json_encode($tx->response_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            </div>
                            @if($tx->checkout_request_id)
                            <p style="font-size: 0.78rem; color: #666; margin-top: 0.75rem;">
                                <strong>Checkout Request ID:</strong>
                                <span style="font-family: monospace;">{{ $tx->checkout_request_id }}</span>
                            </p>
                            @endif
                            @if($tx->originator_conversation_id)
                            <p style="font-size: 0.78rem; color: #666; margin-top: 0.35rem;">
                                <strong>Originator Conversation ID:</strong>
                                <span style="font-family: monospace;">{{ $tx->originator_conversation_id }}</span>
                            </p>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($transactions->hasPages())
            <div class="pagination">
                @if($transactions->onFirstPage())
                    <span style="opacity: 0.45;">&laquo; Prev</span>
                @else
                    <a href="{{ $transactions->previousPageUrl() }}">&laquo; Prev</a>
                @endif

                @foreach($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                    @if($page == $transactions->currentPage())
                        <span class="active-page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach

                @if($transactions->hasMorePages())
                    <a href="{{ $transactions->nextPageUrl() }}">Next &raquo;</a>
                @else
                    <span style="opacity: 0.45;">Next &raquo;</span>
                @endif
            </div>
        @endif
    @endif
</div>

@push('styles')
<script>
    function toggleDetails(id) {
        const row = document.getElementById('details-' + id);
        row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
    }
</script>
@endpush

@endsection
