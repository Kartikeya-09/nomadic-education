@extends('student.layout')

@section('title', 'Learning Hub')
@section('page_title', 'Learning Hub')
@section('active_nav', 'courses')

@section('content')
<div class="card">
    <h3 style="margin-top:0;">My Classes</h3>
    <div class="grid" style="grid-template-columns: repeat(2, minmax(220px, 1fr)); margin-top: 10px;">
        @forelse($classes as $class)
            <div class="card">
                <strong>{{ $class->name ?? 'Class' }}</strong>
                <div class="muted" style="margin:6px 0 10px;">{{ $class->section ?? 'Section' }}</div>
                <a class="btn-primary" href="{{ route('student.class', $class->_id) }}">Open Class</a>
            </div>
        @empty
            <div class="muted">No classes assigned yet.</div>
        @endforelse
    </div>
</div>
@endsection
