@extends('student.layout')

@section('title', $lesson->title ?? 'Lesson')
@section('page_title', 'Lesson')
@section('active_nav', 'courses')

@section('content')
<div class="card">
    <div style="display:flex; justify-content:space-between; gap:8px; align-items:center; margin-bottom:10px;">
        <div>
            <h3 style="margin:0;">{{ $lesson->title ?? 'Lesson' }}</h3>
            <div class="muted">{{ $lesson->topic ?? 'Topic' }}</div>
        </div>
        <a class="btn-ghost" href="{{ route('student.class', $lesson->class_id) }}">Back to Class</a>
    </div>
    <p class="muted" style="margin-bottom:12px;">{{ $lesson->notes ?? $lesson->objectives ?? 'Lesson details coming soon.' }}</p>

    <div style="width:100%; height:320px; border-radius:14px; border:1px solid var(--border-color); background:#000; display:flex; align-items:center; justify-content:center; overflow:hidden; margin-bottom:12px;">
        @if(!empty($videoContent?->file_url))
            <video controls style="width: 100%; height: 100%; object-fit: cover;">
                <source src="{{ asset($videoContent->file_url) }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        @else
            <span class="muted">Video not available yet.</span>
        @endif
    </div>

    @if($isCompleted)
        <div class="alert success" style="margin:0;">You already completed this lesson.</div>
    @else
        <form action="{{ route('student.lesson.complete', $lesson->_id) }}" method="POST">
            @csrf
            <button class="btn-primary" type="submit">Mark as Complete</button>
        </form>
    @endif
</div>
@endsection
