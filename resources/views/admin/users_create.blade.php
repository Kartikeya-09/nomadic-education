@extends('admin.layout')

@section('title', 'Add User')
@section('page_title', 'Add New User')
@section('active_nav', 'create-user')

@section('content')
<div class="card" style="max-width: 680px;">
    <div class="muted" style="margin-bottom: 12px;">Create a new account for student, teacher, parent, or admin.</div>
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="grid" style="grid-template-columns: 1fr 1fr;">
            <div>
                <label class="muted" style="display:block; margin-bottom:6px;">Full Name</label>
                <input type="text" name="name" required value="{{ old('name') }}" placeholder="e.g. Amina Noor" style="width:100%;">
            </div>
            <div>
                <label class="muted" style="display:block; margin-bottom:6px;">Role</label>
                <select name="role" required style="width:100%;">
                    <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student</option>
                    <option value="teacher" {{ old('role') === 'teacher' ? 'selected' : '' }}>Teacher</option>
                    <option value="parent" {{ old('role') === 'parent' ? 'selected' : '' }}>Parent</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
        </div>

        <div style="margin-top: 10px;">
            <label class="muted" style="display:block; margin-bottom:6px;">Login ID (email / phone / username)</label>
            <input type="text" name="login" required value="{{ old('login') }}" placeholder="e.g. amina@school.org" style="width:100%;">
        </div>

        <div style="margin-top: 10px;">
            <label class="muted" style="display:block; margin-bottom:6px;">Temporary Password</label>
            <input type="password" name="password" required placeholder="Minimum 6 characters" style="width:100%;">
        </div>

        <div style="display:flex; gap:8px; margin-top: 14px;">
            <button type="submit" class="btn-primary">Create User</button>
            <a href="{{ route('admin.users') }}" class="btn-ghost">Cancel</a>
        </div>
    </form>
</div>
@endsection
