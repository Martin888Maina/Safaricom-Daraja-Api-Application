<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'M-Pesa Daraja API') — Portfolio</title>
    <style>
        :root {
            --green-primary: #009900;
            --green-dark:    #007700;
            --green-light:   #e6f4e6;
            --white:         #ffffff;
            --gray-100:      #f8f9fa;
            --gray-200:      #e9ecef;
            --gray-700:      #495057;
            --red-error:     #dc3545;
            --shadow:        0 2px 8px rgba(0,0,0,0.1);
            --radius:        8px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--gray-100);
            color: var(--gray-700);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Nav ── */
        nav {
            background: var(--green-primary);
            padding: 0 1.5rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-inner {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .nav-brand {
            color: var(--white);
            font-size: 1.15rem;
            font-weight: 700;
            text-decoration: none;
            padding: 1rem 0;
            letter-spacing: 0.3px;
        }

        .nav-links {
            display: flex;
            flex-wrap: wrap;
            gap: 0.1rem;
        }

        .nav-links a {
            color: rgba(255,255,255,0.88);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.65rem 0.85rem;
            border-radius: var(--radius) var(--radius) 0 0;
            transition: background 0.15s, color 0.15s;
        }

        .nav-links a:hover,
        .nav-links a.active {
            background: rgba(255,255,255,0.18);
            color: var(--white);
        }

        /* ── Main ── */
        main {
            flex: 1;
            max-width: 1100px;
            width: 100%;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        /* ── Cards ── */
        .card {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 2rem;
            margin-bottom: 1.5rem;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--green-dark);
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--green-light);
        }

        /* ── Forms ── */
        .form-group {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.4rem;
            color: var(--gray-700);
        }

        input[type="text"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 0.65rem 0.85rem;
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius);
            font-size: 0.95rem;
            transition: border-color 0.15s, box-shadow 0.15s;
            background: var(--white);
            color: var(--gray-700);
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--green-primary);
            box-shadow: 0 0 0 3px rgba(0,153,0,0.12);
        }

        .hint {
            font-size: 0.78rem;
            color: #888;
            margin-top: 0.3rem;
        }

        /* ── Buttons ── */
        .btn {
            display: inline-block;
            padding: 0.7rem 1.75rem;
            border: none;
            border-radius: var(--radius);
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s, transform 0.1s;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--green-primary);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--green-dark);
            transform: translateY(-1px);
        }

        /* ── Validation errors ── */
        .error-list {
            background: #fff5f5;
            border: 1.5px solid var(--red-error);
            border-radius: var(--radius);
            padding: 1rem 1.25rem;
            margin-bottom: 1.25rem;
            list-style: none;
        }

        .error-list li {
            color: var(--red-error);
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .error-list li:last-child { margin-bottom: 0; }

        /* ── Response card ── */
        .response-card {
            border-radius: var(--radius);
            padding: 1.25rem 1.5rem;
            margin-top: 1.5rem;
        }

        .response-card.success {
            background: var(--green-light);
            border: 1.5px solid var(--green-primary);
        }

        .response-card.error {
            background: #fff5f5;
            border: 1.5px solid var(--red-error);
        }

        .response-card h3 {
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }

        .response-card.success h3 { color: var(--green-dark); }
        .response-card.error h3   { color: var(--red-error); }

        .response-card pre {
            font-family: 'Courier New', Courier, monospace;
            font-size: 0.82rem;
            white-space: pre-wrap;
            word-break: break-all;
            line-height: 1.55;
        }

        /* ── Page header ── */
        .page-header {
            margin-bottom: 1.75rem;
        }

        .page-header h1 {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--green-dark);
            margin-bottom: 0.35rem;
        }

        .page-header p {
            font-size: 0.95rem;
            color: #666;
            max-width: 640px;
        }

        /* ── How it works ── */
        .how-it-works {
            background: var(--green-light);
            border-left: 4px solid var(--green-primary);
            border-radius: 0 var(--radius) var(--radius) 0;
            padding: 1rem 1.25rem;
            margin-top: 1.5rem;
            font-size: 0.88rem;
            line-height: 1.6;
            color: #2d5a2d;
        }

        .how-it-works strong { display: block; margin-bottom: 0.35rem; }

        /* ── Table ── */
        .table-wrap { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        th {
            background: var(--green-primary);
            color: var(--white);
            padding: 0.75rem 1rem;
            text-align: left;
            font-weight: 600;
        }

        td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--gray-200);
            vertical-align: top;
        }

        tr:hover td { background: var(--green-light); }

        .badge {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-success  { background: #d4edda; color: #155724; }
        .badge-pending  { background: #fff3cd; color: #856404; }
        .badge-failed   { background: #f8d7da; color: #721c24; }

        /* ── Pagination ── */
        .pagination {
            display: flex;
            gap: 0.4rem;
            margin-top: 1.25rem;
            flex-wrap: wrap;
        }

        .pagination a,
        .pagination span {
            padding: 0.4rem 0.8rem;
            border-radius: var(--radius);
            font-size: 0.85rem;
            text-decoration: none;
            border: 1.5px solid var(--gray-200);
            color: var(--gray-700);
        }

        .pagination a:hover          { border-color: var(--green-primary); color: var(--green-primary); }
        .pagination span.active-page { background: var(--green-primary); color: var(--white); border-color: var(--green-primary); }

        /* ── Footer ── */
        footer {
            background: var(--green-dark);
            color: rgba(255,255,255,0.75);
            text-align: center;
            padding: 1rem;
            font-size: 0.8rem;
            margin-top: auto;
        }

        footer a { color: rgba(255,255,255,0.9); }
    </style>
    @stack('styles')
</head>
<body>

<nav>
    <div class="nav-inner">
        <a href="{{ route('home') }}" class="nav-brand">M-Pesa Daraja API</a>
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
    Built by Martin Kamau Maina &mdash; Laravel &middot; PHP &middot; MySQL &middot; Safaricom Daraja API &middot; Pure CSS
</footer>

</body>
</html>
