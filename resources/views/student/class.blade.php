@extends('student.layout')

@section('title', $class->name ?? 'Class')
@section('page_title', 'Class Lessons')
@section('active_nav', 'courses')

@section('content')
<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:8px; margin-bottom:10px;">
        <div>
            <h3 style="margin:0;">{{ $class->name ?? 'Class' }}</h3>
            <div class="muted">{{ $class->section ?? 'Section' }}</div>
        </div>
        @if($classCompleted)
            <a class="btn-primary" href="{{ route('student.quiz', $class->_id) }}">Start Quiz</a>
        @endif
    </div>

    <div class="grid" style="grid-template-columns: 1fr;">
        @forelse($class->lessons as $lesson)
            @php
                $lessonId = (string) $lesson->_id;
                $isCompleted = in_array($lessonId, $completedLessons, true);
            @endphp
            <div class="card" style="padding:12px;">
                <div style="display:flex; justify-content:space-between; gap:8px; align-items:center;">
                    <div>
                        <strong>{{ $lesson->title ?? 'Lesson' }}</strong>
                        <div class="muted">{{ $lesson->topic ?? 'Topic' }}</div>
                    </div>
                    <a class="btn-ghost" href="{{ route('student.lesson', $lesson->_id) }}">{{ $isCompleted ? 'Review Lesson' : 'Start Lesson' }}</a>
                </div>
            </div>
        @empty
            <div class="muted">No lessons available yet.</div>
        @endforelse
    </div>
</div>

<div class="card">
    <h3 style="margin-top:0;">Latest Quiz Result</h3>
    @if($classQuiz)
        <div>You scored <strong>{{ $classQuiz['score'] }}</strong> out of <strong>{{ $classQuiz['total'] }}</strong>.</div>
        <div class="muted" style="margin-top:6px;">Taken at {{ $classQuiz['taken_at'] }}</div>
    @else
        <div class="muted">Complete all lessons to unlock the quiz.</div>
    @endif
</div>
@endsection
