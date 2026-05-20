@extends('admin.layout')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('active_nav', 'dashboard')

@section('content')
<div class="grid" style="grid-template-columns: repeat(4, minmax(120px, 1fr)); margin-bottom: 12px;">
    <div class="card">
        <div class="muted">Students</div>
        <h2 style="margin:8px 0 0;">{{ $stats['students'] ?? 0 }}</h2>
    </div>
    <div class="card">
        <div class="muted">Teachers</div>
        <h2 style="margin:8px 0 0;">{{ $stats['teachers'] ?? 0 }}</h2>
    </div>
    <div class="card">
        <div class="muted">Active Camps</div>
        <h2 style="margin:8px 0 0;">{{ $stats['camps'] ?? 0 }}</h2>
    </div>
    <div class="card">
        <div class="muted">Quick Actions</div>
        <div style="margin-top:10px; display:flex; gap:8px; flex-wrap:wrap;">
            <a href="{{ route('admin.users') }}" class="btn-ghost">Manage Users</a>
            <a href="{{ route('admin.users.create') }}" class="btn-primary">Add User</a>
        </div>
    </div>
</div>

<div class="grid" style="grid-template-columns: 2fr 1fr;">
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
            <h3 style="margin:0;">Recently Registered Users</h3>
            <a href="{{ route('admin.users') }}" class="btn-ghost">Open User Management</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Login</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentUsers as $user)
                        @php $roleName = $user->roles->first()->name ?? 'student'; @endphp
                        <tr>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td><span class="role-badge {{ $roleName }}">{{ ucfirst($roleName) }}</span></td>
                            <td>{{ $user->email ?? $user->phone ?? $user->guardian_phone ?? 'N/A' }}</td>
                            <td>{{ $user->created_at?->format('M d, Y h:i A') ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="muted">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <h3 style="margin-top:0;">System Overview</h3>
        <div class="muted" style="line-height:1.5;">
            This dashboard is now connected only to working admin pages.
            Use sidebar links for stable navigation.
        </div>
        <div style="margin-top:12px; display:flex; flex-direction:column; gap:8px;">
            <a href="{{ route('admin.users') }}" class="btn-ghost">Go to User Management</a>
            <a href="{{ route('admin.users.create') }}" class="btn-ghost">Create New User</a>
        </div>
    </div>
</div>
@endsection
