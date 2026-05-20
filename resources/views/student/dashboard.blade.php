@extends('student.layout')

@section('title', 'Dashboard')
@section('page_title', 'Welcome')
@section('active_nav', 'dashboard')

@section('content')
<div class="grid" style="grid-template-columns: repeat(4, minmax(140px, 1fr));">
    <div class="card">
        <div class="muted">Classes</div>
        <h2 style="margin:8px 0 0;">{{ $classes->count() }}</h2>
    </div>
    <div class="card">
        <div class="muted">Lessons Completed</div>
        <h2 style="margin:8px 0 0;">{{ $completedCount }}</h2>
    </div>
    <div class="card">
        <div class="muted">Pending Lessons</div>
        <h2 style="margin:8px 0 0;">{{ $pendingLessons }}</h2>
    </div>
    <div class="card">
        <div class="muted">Achievements</div>
        <h2 style="margin:8px 0 0;">{{ count($achievements) }}</h2>
    </div>
</div>

<div class="grid" style="grid-template-columns: 2fr 1fr; margin-top: 12px;">
    <div class="card">
        <div style="display:flex; justify-content:space-between; gap:8px; align-items:center; margin-bottom:10px;">
            <h3 style="margin:0;">Learning Progress</h3>
            <a class="btn-ghost" href="{{ route('student.progress') }}">Open Progress Tracker</a>
        </div>
        <div class="muted">{{ $completedCount }} completed of {{ $totalLessons }} lessons</div>
        <div style="height:10px; border-radius:999px; background:var(--border-color); margin:10px 0; overflow:hidden;">
            <div style="height:100%; width:{{ $progressPercent }}%; background:var(--primary);"></div>
        </div>
        <strong>{{ $progressPercent }}% completed</strong>

        <h3 style="margin:16px 0 8px;">Current Classes</h3>
        <div class="grid" style="grid-template-columns: 1fr;">
            @forelse($classes as $class)
                <div class="card" style="padding:12px;">
                    <div style="display:flex; justify-content:space-between; gap:8px; align-items:center;">
                        <div>
                            <strong>{{ $class->name ?? 'Class' }}</strong>
                            <div class="muted" style="font-size:13px;">
                                {{ $class->section ?? 'Section' }} · {{ $lessonCounts[(string) $class->_id] ?? 0 }} lessons
                            </div>
                        </div>
                        <a class="btn-primary" href="{{ route('student.class', $class->_id) }}">Open Class</a>
                    </div>
                </div>
            @empty
                <div class="muted">No classes assigned yet.</div>
            @endforelse
        </div>
    </div>

    <div class="grid" style="grid-template-columns: 1fr;">
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                <h3 style="margin:0;">Achievements</h3>
                <a class="btn-ghost" href="{{ route('student.achievements') }}">View All</a>
            </div>
            <div class="grid" style="grid-template-columns: 1fr;">
                @forelse($achievements as $achievement)
                    <div class="card" style="padding:10px;">
                        <strong>{{ $achievement }}</strong>
                    </div>
                @empty
                    <div class="muted">Complete lessons and quizzes to unlock achievements.</div>
                @endforelse
            </div>
        </div>

        <div class="card">
            <h3 style="margin-top:0;">Quiz Summary</h3>
            @if($latestQuiz)
                <div><strong>Latest:</strong> {{ $latestQuiz['score'] ?? 0 }}/{{ $latestQuiz['total'] ?? 0 }} ({{ $latestQuiz['percent'] ?? 0 }}%)</div>
                <div class="muted" style="margin-top:6px;">Taken at {{ $latestQuiz['taken_at'] ?? 'N/A' }}</div>
                <div style="margin-top:10px;">
                    <div class="muted">Quizzes Taken</div>
                    <strong>{{ $quizCount }}</strong>
                </div>
                <div style="margin-top:8px;">
                    <div class="muted">Average Score</div>
                    <strong>{{ $avgQuizPercent }}%</strong>
                </div>
            @else
                <div class="muted">No quiz attempts yet. Complete lessons to unlock your first quiz.</div>
            @endif
        </div>
    </div>
</div>
@endsection
