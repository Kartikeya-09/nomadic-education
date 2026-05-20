@extends('admin.layout')

@section('title', 'User Management')
@section('page_title', 'User Management')
@section('active_nav', 'users')

@section('content')
<div class="grid" style="grid-template-columns: repeat(3, minmax(140px, 1fr)); margin-bottom: 12px;">
    <div class="card">
        <div class="muted">Total Users</div>
        <h2 style="margin:8px 0 0;">{{ $summary['total'] ?? $users->count() }}</h2>
    </div>
    <div class="card">
        <div class="muted">Students</div>
        <h2 style="margin:8px 0 0;">{{ $summary['students'] ?? 0 }}</h2>
    </div>
    <div class="card">
        <div class="muted">Teachers</div>
        <h2 style="margin:8px 0 0;">{{ $summary['teachers'] ?? 0 }}</h2>
    </div>
</div>

<div class="card">
    <div id="ajaxMessage" class="alert" style="display:none;"></div>
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; gap:10px; flex-wrap:wrap;">
        <h3 style="margin:0;">Registered Users ({{ ucfirst($roleFilter ?? 'all') }})</h3>
        <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
            <form method="GET" action="{{ route('admin.users') }}" style="display:flex; gap:8px; align-items:center;">
                <select name="role" style="min-width: 170px;">
                    <option value="all" {{ ($roleFilter ?? 'all') === 'all' ? 'selected' : '' }}>All Users</option>
                    <option value="teacher" {{ ($roleFilter ?? 'all') === 'teacher' ? 'selected' : '' }}>Teachers</option>
                    <option value="student" {{ ($roleFilter ?? 'all') === 'student' ? 'selected' : '' }}>Students</option>
                </select>
                <button class="btn-ghost" type="submit">Filter</button>
            </form>
            <a href="{{ route('admin.users.create') }}" class="btn-primary">Add New User</a>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Login</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th>Assigned Teacher</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    @php
                        $roleName = $user->roles->first()->name ?? 'student';
                        $assignedTeacherId = $assignments[(string) $user->getKey()] ?? null;
                        $assignedTeacher = $assignedTeacherId
                            ? $teachers->first(fn ($t) => (string) $t->getKey() === (string) $assignedTeacherId)
                            : null;
                    @endphp
                    <tr>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->email ?? $user->phone ?? $user->guardian_phone ?? 'N/A' }}</td>
                        <td><span class="role-badge {{ $roleName }}">{{ ucfirst($roleName) }}</span></td>
                        <td>{{ $user->created_at?->format('M d, Y') ?? 'N/A' }}</td>
                        <td class="js-assigned-teacher">
                            @if($roleName === 'student')
                                {{ $assignedTeacher?->name ?? 'Unassigned' }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($roleName === 'student')
                                <form class="js-assign-teacher" action="#" method="POST" style="display:flex; gap:8px; align-items:center; margin-bottom:8px; flex-wrap:wrap;" data-user-id="{{ (string) $user->getKey() }}" data-assign-url="{{ route('admin.users.assign_teacher_simple') }}">
                                    @csrf
                                    <select name="teacher_id" style="min-width: 190px;">
                                        <option value="">Unassigned</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ (string) $teacher->getKey() }}" {{ (string) $assignedTeacherId === (string) $teacher->getKey() ? 'selected' : '' }}>
                                                {{ $teacher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button class="btn-ghost" type="submit">Assign Teacher</button>
                                </form>
                                <form action="{{ route('admin.users.assign_class', (string) $user->getKey()) }}" method="POST" style="display:flex; gap:8px; align-items:center; margin-bottom:8px; flex-wrap:wrap;">
                                    @csrf
                                    <select name="class_id" style="min-width: 210px;">
                                        @foreach($classes as $class)
                                            <option value="{{ (string) $class->getKey() }}" {{ (string) $user->class_id === (string) $class->getKey() ? 'selected' : '' }}>
                                                {{ $class->name }}{{ $class->section ? ' - '.$class->section : '' }}{{ $class->teacher ? ' ('.$class->teacher->name.')' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button class="btn-ghost" type="submit">Assign Class</button>
                                </form>
                            @endif
                            <button class="btn-ghost" onclick="openPasswordModal('{{ (string) $user->getKey() }}', '{{ $user->name }}')">Reset Password</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="passwordModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:9999; align-items:center; justify-content:center;">
    <div class="card" style="width: 420px; max-width: 95vw; position:relative;">
        <button onclick="closePasswordModal()" style="position:absolute; right:12px; top:8px; border:none; background:transparent; color:var(--text-muted); font-size:22px; cursor:pointer;">&times;</button>
        <h3 style="margin-top:0;">Reset Password</h3>
        <p class="muted">Reset password for <strong id="modalUserName"></strong></p>
        <form id="resetForm" method="POST" action="">
            @csrf
            <input type="password" name="new_password" required placeholder="New password (min 6 chars)" style="width:100%; margin: 8px 0 10px;">
            <button type="submit" class="btn-primary">Confirm Reset</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openPasswordModal(id, name) {
        document.getElementById('modalUserName').textContent = name;
        document.getElementById('resetForm').action = `/admin/users/${id}/reset-password`;
        document.getElementById('passwordModal').style.display = 'flex';
    }

    function closePasswordModal() {
        document.getElementById('passwordModal').style.display = 'none';
    }

    function showAjaxMessage(message, isError) {
        const el = document.getElementById('ajaxMessage');
        el.textContent = message;
        el.style.display = 'block';
        el.className = isError ? 'alert error' : 'alert success';
        if (!isError) {
            el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    document.querySelectorAll('.js-assign-teacher').forEach((form) => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const select = form.querySelector('select[name="teacher_id"]');
            const teacherId = select ? select.value : '';
            const url = form.getAttribute('data-assign-url');
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify({ user_id: form.dataset.userId, teacher_id: teacherId }),
                });

                const contentType = response.headers.get('content-type') || '';
                const data = contentType.includes('application/json')
                    ? await response.json()
                    : { message: 'Unexpected response from server.' };

                if (!response.ok) {
                    throw new Error(data.message || 'Failed to assign teacher.');
                }

                const teacherCell = form.closest('tr')?.querySelector('.js-assigned-teacher');
                if (teacherCell) {
                    const selectedName = select?.options?.[select.selectedIndex]?.text?.trim();
                    teacherCell.textContent = data.teacher_name || selectedName || 'Unassigned';
                }

                showAjaxMessage(data.message || 'Teacher assigned.', false);
            } catch (error) {
                showAjaxMessage(error.message || 'Failed to assign teacher.', true);
            }
        });
    });
</script>
@endpush
