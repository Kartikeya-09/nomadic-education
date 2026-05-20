@extends('student.layout')

@section('title', 'Progress Tracker')
@section('page_title', 'Progress Tracker')
@section('active_nav', 'progress')

@section('content')
<div class="card">
    <h3 style="margin-top:0;">Lesson Completion</h3>
    <div class="muted">{{ $completedCount }} completed of {{ $totalLessons }} lessons</div>
    <div style="height:12px; border-radius:999px; background:var(--border-color); margin:12px 0; overflow:hidden;">
        <div style="height:100%; width:{{ $progressPercent }}%; background:var(--primary);"></div>
    </div>
    <strong>{{ $progressPercent }}% completed</strong>
</div>
@endsection
