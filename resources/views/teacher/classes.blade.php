@extends('teacher.layout')

@section('title', 'Class Manager')
@section('page_title', 'Class Manager')
@section('active_nav', 'classes')

@section('content')
<div class="grid" style="grid-template-columns: repeat(3, minmax(180px, 1fr));">
    @forelse($classes as $class)
        @php $stat = $classStats[(string) $class->getKey()] ?? ['students' => 0, 'avg_progress' => 0]; @endphp
        <div class="card">
            <h3 style="margin-top:0;">{{ $class->name }}{{ $class->section ? ' - '.$class->section : '' }}</h3>
            <div class="muted">Academic year: {{ $class->academic_year ?? 'N/A' }}</div>
            <div style="margin-top:10px;"><span class="pill">{{ $stat['students'] }} students</span></div>
            <div style="margin-top:10px;"><span class="pill">{{ $stat['avg_progress'] }}% avg completion</span></div>
            <div style="margin-top:12px;">
                <a class="btn-ghost" href="{{ route('teacher.students') }}">Manage students</a>
            </div>
        </div>
    @empty
        <div class="card muted">No classes assigned to you yet.</div>
    @endforelse
</div>
@endsection
