@extends('layouts.app')

@section('title', 'Transaction History')

@section('content')

<div class="page-header">
    <h1>Transaction History</h1>
    <p>Every Daraja API call logged with full request and response payloads.</p>
</div>

@if(session('flash_success'))
    <div style="background: var(--success-bg); border: 1px solid var(--success-bd); color: var(--success-tx); padding: 0.75rem 1rem; border-radius: var(--radius); margin-bottom: 1.25rem; font-size: 0.875rem;">
        {{ session('flash_success') }}
    </div>
@endif

<div class="card">
    {{-- Header: title + search --}}
    <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.25rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border); flex-wrap: wrap;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <span style="font-size: 0.8125rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: var(--text-muted);">All Transactions</span>
            <span style="font-size: 0.78rem; color: var(--text-muted); background: var(--bg); border: 1px solid var(--border); padding: 0.1rem 0.55rem; border-radius: 20px;">{{ $transactions->total() }}</span>
        </div>
        <form method="GET" action="{{ route('history') }}" style="display: flex; gap: 0.5rem; align-items: center;">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search phone, API, status..."
                style="width: 260px; padding: 0.45rem 0.75rem; font-size: 0.855rem; border: 1px solid var(--border); border-radius: var(--radius); font-family: inherit; background: var(--bg); color: var(--text);"
            >
            <button type="submit" class="btn btn-primary" style="padding: 0.45rem 1rem; font-size: 0.855rem;">Search</button>
            @if(request('search'))
                <a href="{{ route('history') }}" style="font-size: 0.83rem; color: var(--text-muted); text-decoration: none; white-space: nowrap;">Clear</a>
            @endif
        </form>
    </div>

    @if($transactions->isEmpty())
        <p style="color: var(--text-muted); font-size: 0.875rem; padding: 2rem 0;">
            {{ request('search') ? 'No transactions matched your search.' : 'No transactions recorded yet.' }}
        </p>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date / Time</th>
                        <th>API</th>
                        <th>Phone</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Result</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $tx)
                    <tr>
                        <td style="color: var(--text-muted); font-size: 0.78rem; font-family: 'JetBrains Mono', monospace;">{{ $tx->id }}</td>
                        <td style="white-space: nowrap; font-size: 0.82rem; color: var(--text-muted);">
                            {{ $tx->created_at->format('d M Y') }}<br>
                            {{ $tx->created_at->format('H:i:s') }}
                        </td>
                        <td><span class="mono-tag">{{ $tx->api_type }}</span></td>
                        <td style="font-family: 'JetBrains Mono', monospace; font-size: 0.82rem;">{{ $tx->phone_number ?? '—' }}</td>
                        <td style="font-variant-numeric: tabular-nums; white-space: nowrap;">{{ $tx->amount ? 'KES ' . number_format($tx->amount, 2) : '—' }}</td>
                        <td><span class="badge badge-{{ $tx->status }}">{{ $tx->status }}</span></td>
                        <td style="font-size: 0.82rem; max-width: 180px;">
                            @if($tx->result_code !== null)
                                <span style="font-family: 'JetBrains Mono', monospace; font-size: 0.78rem;">{{ $tx->result_code }}</span><br>
                                <span style="color: var(--text-muted);">{{ Str::limit($tx->result_desc, 35) }}</span>
                            @else
                                <span style="color: var(--text-muted);">—</span>
                            @endif
                        </td>
                        <td style="white-space: nowrap;">
                            <div style="display: flex; gap: 0.4rem; align-items: center;">
                                {{-- View --}}
                                <a href="{{ route('transactions.show', $tx) }}"
                                   style="font-size: 0.78rem; font-weight: 500; color: var(--accent); text-decoration: none; padding: 0.25rem 0.6rem; border: 1px solid var(--border); border-radius: var(--radius); background: var(--surface);"
                                   title="View">View</a>

                                {{-- Edit --}}
                                <a href="{{ route('transactions.edit', $tx) }}"
                                   style="font-size: 0.78rem; font-weight: 500; color: var(--text); text-decoration: none; padding: 0.25rem 0.6rem; border: 1px solid var(--border); border-radius: var(--radius); background: var(--surface);"
                                   title="Edit">Edit</a>

                                {{-- Delete --}}
                                <form method="POST" action="{{ route('transactions.destroy', $tx) }}" onsubmit="return confirm('Delete transaction #{{ $tx->id }}? This cannot be undone.');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        style="font-size: 0.78rem; font-weight: 500; color: var(--error-tx); background: var(--surface); border: 1px solid var(--error-bd); border-radius: var(--radius); padding: 0.25rem 0.6rem; cursor: pointer; font-family: inherit;"
                                        title="Delete">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($transactions->hasPages())
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.25rem; flex-wrap: wrap; gap: 0.75rem;">
                <p style="font-size: 0.82rem; color: var(--text-muted);">
                    Showing {{ $transactions->firstItem() }}–{{ $transactions->lastItem() }} of {{ $transactions->total() }} transactions
                </p>
                <div class="pagination">
                    @if($transactions->onFirstPage())
                        <span style="opacity: 0.4;">&laquo; Prev</span>
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
                        <span style="opacity: 0.4;">Next &raquo;</span>
                    @endif
                </div>
            </div>
        @endif
    @endif
</div>

@endsection
