@extends('teacher.layout')

@section('title', 'Student Report')
@section('page_title', 'Student Report')
@section('active_nav', 'reports')

@section('content')
<div class="card" style="margin-bottom: 14px;">
    <h2 style="margin:0 0 6px;">{{ $student->name }}</h2>
    <div class="muted">{{ $student->email ?? $student->phone ?? $student->guardian_phone ?? 'No contact info' }}</div>
</div>

<div class="grid" style="grid-template-columns: repeat(4, minmax(140px, 1fr)); margin-bottom: 14px;">
    <div class="card">
        <div class="muted">Course Completion</div>
        <h2 style="margin:8px 0 0;">{{ $progress['percent'] }}%</h2>
        <div class="muted">{{ $progress['completed'] }}/{{ $progress['total'] }} lessons</div>
    </div>
    <div class="card">
        <div class="muted">Quiz Attempts</div>
        <h2 style="margin:8px 0 0;">{{ $quiz['attempts'] }}</h2>
    </div>
    <div class="card">
        <div class="muted">Average Quiz</div>
        <h2 style="margin:8px 0 0;">{{ $quiz['average_percent'] !== null ? $quiz['average_percent'].'%' : 'N/A' }}</h2>
    </div>
    <div class="card">
        <div class="muted">Current Class</div>
        <h2 style="margin:8px 0 0; font-size:18px;">{{ $currentClass?->name ?? 'Not assigned' }}</h2>
        @if($currentClass?->section)
            <div class="muted">Section {{ $currentClass->section }}</div>
        @endif
    </div>
</div>

<div class="card" style="margin-bottom: 14px;">
    <h3 style="margin-top:0;">Change Student Class</h3>
    <form action="{{ route('teacher.students.assign_class', (string) $student->getKey()) }}" method="POST" style="display:flex; gap:10px; align-items:center; flex-wrap: wrap;">
        @csrf
        <select name="class_id" style="min-width:260px; width:320px; font-size:14px;">
            @forelse($classes as $class)
                <option value="{{ (string) $class->getKey() }}" {{ (string) ($student->class_id ?? '') === (string) $class->getKey() ? 'selected' : '' }}>
                    {{ $class->name }}{{ $class->section ? ' - '.$class->section : '' }}
                </option>
            @empty
                <option value="">No classes available</option>
            @endforelse
        </select>
        <button class="btn-primary" type="submit" {{ $classes->isEmpty() ? 'disabled' : '' }}>Update Class</button>
        <a class="btn-ghost" href="{{ route('teacher.students') }}">Back to students</a>
    </form>
</div>

<div class="grid" style="grid-template-columns: 1fr 1fr;">
    <div class="card">
        <h3 style="margin-top:0;">Completed Lessons</h3>
        @forelse($completedLessons as $lesson)
            <div class="pill" style="margin:0 8px 8px 0;">{{ $lesson->title }}</div>
        @empty
            <div class="muted">No completed lessons yet.</div>
        @endforelse
    </div>

    <div class="card">
        <h3 style="margin-top:0;">Quiz History</h3>
        <div class="table-wrap">
            <table style="min-width: 100%;">
                <thead>
                    <tr>
                        <th>Class</th>
                        <th>Score</th>
                        <th>Percent</th>
                        <th>Taken At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($quizHistory as $row)
                        <tr>
                            <td>{{ $row['class_name'] ?? 'Class' }}</td>
                            <td>{{ (int) ($row['score'] ?? 0) }}/{{ (int) ($row['total'] ?? 0) }}</td>
                            <td>{{ (int) ($row['percent'] ?? 0) }}%</td>
                            <td>{{ $row['taken_at'] ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="muted">No quiz records yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
