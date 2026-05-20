<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Teacher Portal') | NomadEdu</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --primary: #fbbf24;
            --bg-color: #050505;
            --text-color: #ffffff;
            --text-muted: #94a3b8;
            --card-bg: rgba(255, 255, 255, 0.05);
            --border-color: rgba(255, 255, 255, 0.12);
            --sidebar-bg: #0a0a0a;
            --hover-bg: rgba(251, 191, 36, 0.12);
            --danger: #ef4444;
            --success: #22c55e;
        }

        body.light-mode {
            --bg-color: #f8fafc;
            --text-color: #0f172a;
            --text-muted: #475569;
            --card-bg: #ffffff;
            --border-color: rgba(15, 23, 42, 0.12);
            --sidebar-bg: #ffffff;
            --hover-bg: rgba(251, 191, 36, 0.2);
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: var(--bg-color);
            color: var(--text-color);
            font-family: Arial, sans-serif;
            min-height: 100vh;
            display: flex;
        }
        a { color: inherit; }
        .sidebar {
            width: 270px;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            padding: 22px 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .brand {
            color: var(--primary);
            font-size: 32px;
            font-weight: 800;
            text-align: center;
            margin: 6px 0 14px;
        }
        .nav {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 10px;
        }
        .nav-link {
            text-decoration: none;
            padding: 12px 14px;
            border-radius: 10px;
            color: var(--text-muted);
            border: 1px solid transparent;
            font-weight: 600;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--primary);
            background: var(--hover-bg);
            border-color: rgba(251, 191, 36, 0.3);
        }
        .sidebar-actions {
            margin-top: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .btn-link {
            text-decoration: none;
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            color: var(--text-color);
            background: var(--card-bg);
            font-weight: 700;
            text-align: left;
            cursor: pointer;
        }
        .btn-link:hover { border-color: var(--primary); }
        .logout-btn {
            width: 100%;
            text-align: left;
            background: transparent;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-color);
            padding: 12px 14px;
            cursor: pointer;
            font-weight: 700;
        }
        .main {
            flex: 1;
            padding: 24px 28px;
            overflow-y: auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }
        .title {
            font-size: 32px;
            margin: 0;
        }
        .title span { color: var(--primary); }
        .badge {
            border: 1px solid rgba(34, 197, 94, 0.4);
            background: rgba(34, 197, 94, 0.12);
            color: var(--success);
            border-radius: 999px;
            padding: 8px 12px;
            font-weight: 700;
        }
        .alert {
            margin-bottom: 14px;
            padding: 12px 14px;
            border-radius: 8px;
            border: 1px solid transparent;
        }
        .alert.success {
            color: var(--success);
            border-color: rgba(34, 197, 94, 0.4);
            background: rgba(34, 197, 94, 0.12);
        }
        .alert.error {
            color: var(--danger);
            border-color: rgba(239, 68, 68, 0.4);
            background: rgba(239, 68, 68, 0.1);
        }
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 16px;
        }
        .grid {
            display: grid;
            gap: 14px;
        }
        .table-wrap {
            overflow-x: auto;
            border: 1px solid var(--border-color);
            border-radius: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 760px;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid var(--border-color);
            text-align: left;
            vertical-align: middle;
        }
        th { color: var(--text-muted); font-size: 13px; }
        .pill {
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            border: 1px solid var(--border-color);
            display: inline-block;
        }
        .muted { color: var(--text-muted); }
        .btn-primary {
            border: 1px solid var(--primary);
            background: var(--primary);
            color: #111827;
            border-radius: 8px;
            padding: 8px 11px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-ghost {
            border: 1px solid var(--border-color);
            background: transparent;
            color: var(--text-color);
            border-radius: 8px;
            padding: 8px 11px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        input, select {
            background: rgba(0, 0, 0, 0.2);
            color: var(--text-color);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 8px 10px;
        }
        @media (max-width: 980px) {
            .sidebar { width: 210px; }
            .title { font-size: 24px; }
        }
    </style>
</head>
<body>
@php
    $activeNavSection = trim($__env->yieldContent('active_nav'));
    $activeNav = $activeNavSection !== '' ? $activeNavSection : ($activeNav ?? 'dashboard');
@endphp
<aside class="sidebar">
    <div class="brand">NomadEdu</div>
    <nav class="nav">
        <a class="nav-link {{ $activeNav === 'dashboard' ? 'active' : '' }}" href="{{ route('teacher.dashboard') }}">Dashboard</a>
        <a class="nav-link {{ $activeNav === 'students' ? 'active' : '' }}" href="{{ route('teacher.students') }}">Assigned Students</a>
        <a class="nav-link {{ $activeNav === 'classes' ? 'active' : '' }}" href="{{ route('teacher.classes') }}">Class Manager</a>
        <a class="nav-link {{ $activeNav === 'reports' ? 'active' : '' }}" href="{{ route('teacher.reports') }}">Student Reports</a>
        <a class="nav-link {{ $activeNav === 'worksheets' ? 'active' : '' }}" href="{{ route('teacher.worksheets') }}">Worksheets</a>
    </nav>
    <div class="sidebar-actions">
        <button class="btn-link" onclick="toggleTheme()">Toggle Theme</button>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
</aside>
<main class="main">
    <div class="header">
        <h1 class="title">@yield('page_title', 'Teacher Portal') <span>{{ auth()->user()->name }}</span></h1>
        <div class="badge">Live Data</div>
    </div>
    @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif
    @if(session('status'))
        <div class="alert success">{{ session('status') }}</div>
    @endif
    @if($errors->any())
        <div class="alert error">{{ $errors->first() }}</div>
    @endif
    @yield('content')
</main>
<script>
    function toggleTheme() {
        document.body.classList.toggle('light-mode');
    }
</script>
@stack('scripts')
</body>
</html>
