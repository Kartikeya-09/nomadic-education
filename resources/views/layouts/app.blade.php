<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Nomadic Education') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <nav class="bg-white border-b border-slate-200">
        <div class="mx-auto max-w-6xl px-4 sm:px-6">
            <div class="flex h-14 items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-full bg-emerald-600"></div>
                    <span class="text-base font-semibold tracking-tight">Nomadic Education</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-medium text-slate-600">Network:</span>
                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                        <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                        Offline
                    </span>
                    @auth
                        <span class="hidden text-xs text-slate-600 sm:inline">
                            Logged in as: {{ auth()->user()->name }} ({{ auth()->user()->getRoleNames()->first() ?? 'user' }})
                        </span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                Logout
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="mx-auto w-full max-w-6xl px-4 py-6 sm:px-6">
        @yield('content')
    </main>
</body>
</html>
