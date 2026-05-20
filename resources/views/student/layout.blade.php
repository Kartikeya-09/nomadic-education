<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Student Portal') | NomadEdu</title>
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
            font-family: Arial, sans-serif;
            background: var(--bg-color);
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
        }
        a { color: inherit; }
        .sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            padding: 20px 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .brand {
            color: var(--primary);
            font-size: 30px;
            font-weight: 800;
            text-align: center;
            margin: 8px 0 12px;
        }
        .nav {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .nav-link {
            text-decoration: none;
            padding: 12px 14px;
            border-radius: 10px;
            color: var(--text-muted);
            border: 1px solid transparent;
            font-weight: 700;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--primary);
            background: var(--hover-bg);
            border-color: rgba(251, 191, 36, 0.35);
        }
        .sidebar-actions {
            margin-top: auto;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .btn-link, .logout-btn {
            width: 100%;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            background: var(--card-bg);
            color: var(--text-color);
            padding: 11px 12px;
            font-weight: 700;
            text-align: left;
            cursor: pointer;
        }
        .btn-link:hover, .logout-btn:hover { border-color: var(--primary); }
        .main {
            flex: 1;
            overflow-y: auto;
            padding: 24px 28px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }
        .title {
            margin: 0;
            font-size: 30px;
        }
        .title span { color: var(--primary); }
        .pill {
            border-radius: 999px;
            padding: 7px 12px;
            border: 1px solid rgba(34, 197, 94, 0.35);
            background: rgba(34, 197, 94, 0.1);
            color: var(--success);
            font-weight: 700;
            font-size: 13px;
        }
        .grid { display: grid; gap: 12px; }
        .card {
            border: 1px solid var(--border-color);
            background: var(--card-bg);
            border-radius: 14px;
            padding: 16px;
        }
        .muted { color: var(--text-muted); }
        .alert {
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 12px;
            border: 1px solid transparent;
        }
        .alert.success {
            border-color: rgba(34, 197, 94, 0.45);
            background: rgba(34, 197, 94, 0.12);
            color: var(--success);
        }
        .alert.error {
            border-color: rgba(239, 68, 68, 0.45);
            background: rgba(239, 68, 68, 0.12);
            color: var(--danger);
        }
        .btn-primary {
            border: 1px solid var(--primary);
            background: var(--primary);
            color: #111827;
            border-radius: 8px;
            padding: 8px 12px;
            text-decoration: none;
            cursor: pointer;
            font-weight: 700;
            display: inline-block;
        }
        .btn-ghost {
            border: 1px solid var(--border-color);
            background: transparent;
            color: var(--text-color);
            border-radius: 8px;
            padding: 8px 12px;
            text-decoration: none;
            cursor: pointer;
            font-weight: 700;
            display: inline-block;
        }
        .table-wrap {
            overflow-x: auto;
            border: 1px solid var(--border-color);
            border-radius: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border-bottom: 1px solid var(--border-color);
            padding: 12px;
            text-align: left;
            vertical-align: top;
        }
        th { color: var(--text-muted); font-size: 13px; }
        input, select {
            border: 1px solid var(--border-color);
            background: rgba(0, 0, 0, 0.2);
            color: var(--text-color);
            border-radius: 8px;
            padding: 9px 10px;
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
        <a class="nav-link {{ $activeNav === 'dashboard' ? 'active' : '' }}" href="{{ route('student.dashboard') }}">Dashboard</a>
        <a class="nav-link {{ $activeNav === 'courses' ? 'active' : '' }}" href="{{ route('student.courses') }}">Learning Hub</a>
        <a class="nav-link {{ $activeNav === 'progress' ? 'active' : '' }}" href="{{ route('student.progress') }}">Progress Tracker</a>
        <a class="nav-link {{ $activeNav === 'achievements' ? 'active' : '' }}" href="{{ route('student.achievements') }}">Achievements</a>
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
        <h1 class="title">@yield('page_title', 'Student Portal') <span>{{ auth()->user()->name }}</span></h1>
        <div class="pill">Student Portal</div>
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
