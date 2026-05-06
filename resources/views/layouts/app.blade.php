<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Daraja API') — M-Pesa Portfolio</title>
    <link rel="icon" type="image/x-icon" href="/favicon/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="manifest" href="/favicon/site.webmanifest">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:          #f4f5f7;
            --surface:     #ffffff;
            --border:      #dde1e7;
            --text:        #1a1d23;
            --text-muted:  #6b7280;
            --navy:        #1e2a3a;
            --navy-light:  #28384d;
            --accent:      #2563eb;
            --accent-dim:  #eff4ff;
            --success-bg:  #f0fdf4;
            --success-bd:  #86efac;
            --success-tx:  #166534;
            --error-bg:    #fef2f2;
            --error-bd:    #fca5a5;
            --error-tx:    #991b1b;
            --warn-bg:     #fffbeb;
            --warn-tx:     #92400e;
            --radius:      6px;
            --shadow:      0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.04);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 0.9375rem;
            line-height: 1.6;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Nav ── */
        nav {
            background: var(--navy);
            border-bottom: 1px solid rgba(255,255,255,0.06);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-inner {
            max-width: 1080px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .nav-brand {
            color: #fff;
            font-size: 0.9375rem;
            font-weight: 600;
            text-decoration: none;
            padding: 1rem 0;
            letter-spacing: -0.01em;
            white-space: nowrap;
        }

        .nav-brand span {
            color: rgba(255,255,255,0.45);
            font-weight: 400;
            margin-left: 0.35rem;
        }

        .nav-links {
            display: flex;
            align-items: center;
        }

        .nav-links a {
            color: rgba(255,255,255,0.55);
            text-decoration: none;
            font-size: 0.8375rem;
            font-weight: 500;
            padding: 0.5rem 0.8rem;
            border-radius: var(--radius);
            transition: color 0.15s, background 0.15s;
            white-space: nowrap;
        }

        .nav-links a:hover { color: #fff; background: rgba(255,255,255,0.07); }
        .nav-links a.active { color: #fff; background: rgba(255,255,255,0.1); }

        /* ── Main ── */
        main {
            flex: 1;
            max-width: 1080px;
            width: 100%;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        /* ── Cards ── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 1.75rem;
            margin-bottom: 1.25rem;
        }

        .card-title {
            font-size: 0.8125rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-muted);
            margin-bottom: 1.25rem;
            padding-bottom: 0.875rem;
            border-bottom: 1px solid var(--border);
        }

        /* ── Page header ── */
        .page-header { margin-bottom: 1.5rem; }

        .page-header h1 {
            font-size: 1.375rem;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.02em;
            margin-bottom: 0.3rem;
        }

        .page-header p {
            font-size: 0.875rem;
            color: var(--text-muted);
            max-width: 560px;
        }

        /* ── Forms ── */
        .form-group { margin-bottom: 1.125rem; }

        label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.375rem;
        }

        input[type="text"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 0.5625rem 0.75rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 0.9rem;
            font-family: inherit;
            background: var(--surface);
            color: var(--text);
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }

        .hint {
            font-size: 0.78rem;
            color: var(--text-muted);
            margin-top: 0.3rem;
        }

        /* ── Buttons ── */
        .btn {
            display: inline-block;
            padding: 0.5625rem 1.25rem;
            border: none;
            border-radius: var(--radius);
            font-size: 0.875rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            text-decoration: none;
            transition: opacity 0.15s, box-shadow 0.15s;
        }

        .btn-primary {
            background: var(--navy);
            color: #fff;
        }

        .btn-primary:hover { background: var(--navy-light); }

        /* ── Validation errors ── */
        .error-list {
            background: var(--error-bg);
            border: 1px solid var(--error-bd);
            border-radius: var(--radius);
            padding: 0.875rem 1rem;
            margin-bottom: 1.25rem;
            list-style: none;
        }

        .error-list li {
            color: var(--error-tx);
            font-size: 0.855rem;
            margin-bottom: 0.2rem;
        }

        .error-list li:last-child { margin-bottom: 0; }

        /* ── Response card ── */
        .response-card {
            border-radius: var(--radius);
            padding: 1.125rem 1.25rem;
            margin-top: 1.5rem;
            border: 1px solid;
        }

        .response-card.success { background: var(--success-bg); border-color: var(--success-bd); }
        .response-card.error   { background: var(--error-bg);   border-color: var(--error-bd); }

        .response-card h3 {
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.75rem;
        }

        .response-card.success h3 { color: var(--success-tx); }
        .response-card.error h3   { color: var(--error-tx); }

        .response-card pre {
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            font-size: 0.8rem;
            white-space: pre-wrap;
            word-break: break-all;
            line-height: 1.6;
            color: var(--text);
        }

        /* ── How it works ── */
        .how-it-works {
            background: var(--bg);
            border: 1px solid var(--border);
            border-left: 3px solid var(--accent);
            border-radius: 0 var(--radius) var(--radius) 0;
            padding: 0.875rem 1rem;
            margin-top: 1.5rem;
            font-size: 0.855rem;
            line-height: 1.65;
            color: var(--text-muted);
        }

        .how-it-works strong {
            display: block;
            color: var(--text);
            font-weight: 600;
            margin-bottom: 0.3rem;
            font-size: 0.8125rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        /* ── Table ── */
        .table-wrap { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.855rem;
        }

        thead tr {
            border-bottom: 2px solid var(--border);
        }

        th {
            background: transparent;
            color: var(--text-muted);
            padding: 0.625rem 0.875rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        td {
            padding: 0.75rem 0.875rem;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
            color: var(--text);
        }

        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: var(--bg); }

        .mono-tag {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.75rem;
            background: var(--bg);
            border: 1px solid var(--border);
            padding: 0.1rem 0.45rem;
            border-radius: 4px;
            white-space: nowrap;
        }

        .badge {
            display: inline-block;
            padding: 0.15rem 0.55rem;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .badge-success { background: var(--success-bg); color: var(--success-tx); border: 1px solid var(--success-bd); }
        .badge-pending { background: var(--warn-bg);    color: var(--warn-tx);    border: 1px solid #fcd34d; }
        .badge-failed  { background: var(--error-bg);   color: var(--error-tx);   border: 1px solid var(--error-bd); }

        /* ── Pagination ── */
        .pagination {
            display: flex;
            gap: 0.3rem;
            margin-top: 1.25rem;
            flex-wrap: wrap;
        }

        .pagination a,
        .pagination span {
            padding: 0.35rem 0.7rem;
            border-radius: var(--radius);
            font-size: 0.82rem;
            text-decoration: none;
            border: 1px solid var(--border);
            color: var(--text-muted);
        }

        .pagination a:hover          { border-color: var(--accent); color: var(--accent); }
        .pagination span.active-page { background: var(--navy); color: #fff; border-color: var(--navy); }

        /* ── Footer ── */
        footer {
            background: var(--navy);
            color: rgba(255,255,255,0.4);
            text-align: center;
            padding: 1rem 1.5rem;
            font-size: 0.78rem;
            margin-top: auto;
        }
    </style>
    @stack('styles')
</head>
<body>

<nav>
    <div class="nav-inner">
        <a href="{{ route('home') }}" class="nav-brand">
            Daraja API <span>/ Portfolio</span>
        </a>
        <div class="nav-links">
            <a href="{{ route('home') }}"           class="{{ request()->routeIs('home') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('stk-push.form') }}"  class="{{ request()->routeIs('stk-push*') ? 'active' : '' }}">STK Push</a>
            <a href="{{ route('stk-query.form') }}" class="{{ request()->routeIs('stk-query*') ? 'active' : '' }}">STK Query</a>
            <a href="{{ route('c2b.form') }}"        class="{{ request()->routeIs('c2b*') ? 'active' : '' }}">C2B Simulate</a>
            <a href="{{ route('b2c.form') }}"        class="{{ request()->routeIs('b2c*') ? 'active' : '' }}">B2C Payment</a>
            <a href="{{ route('history') }}"         class="{{ request()->routeIs('history') ? 'active' : '' }}">History</a>
        </div>
    </div>
</nav>

<main>
    @yield('content')
</main>

<footer>
    Martin Kamau Maina &mdash; Laravel &middot; PHP &middot; MySQL &middot; Safaricom Daraja API
</footer>

</body>
</html>
