@extends('teacher.layout')

@section('title', 'Student Reports')
@section('page_title', 'Student Reports')
@section('active_nav', 'reports')

@section('content')
<div class="grid" style="grid-template-columns: repeat(3, minmax(140px, 1fr)); margin-bottom: 14px;">
    <div class="card">
        <div class="muted">Average Progress</div>
        <h2 style="margin:8px 0 0;">{{ $summary['avg_progress'] }}%</h2>
    </div>
    <div class="card">
        <div class="muted">Average Quiz Score</div>
        <h2 style="margin:8px 0 0;">{{ $summary['avg_quiz'] }}%</h2>
    </div>
    <div class="card">
        <div class="muted">Pending Lessons</div>
        <h2 style="margin:8px 0 0;">{{ $summary['pending_lessons'] }}</h2>
    </div>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Course Completion</th>
                <th>Quiz Attempts</th>
                <th>Average Quiz</th>
                <th>Latest Quiz</th>
                <th>Detailed Report</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                @php
                    $studentId = (string) $student->getKey();
                    $progress = $progressByStudent[$studentId] ?? ['completed' => 0, 'total' => 0, 'percent' => 0];
                    $quiz = $quizByStudent[$studentId] ?? ['attempts' => 0, 'average_percent' => null, 'latest' => null];
                    $latest = $quiz['latest'] ?? null;
                @endphp
                <tr>
                    <td>{{ $student->name }}</td>
                    <td>{{ $progress['completed'] }}/{{ $progress['total'] }} ({{ $progress['percent'] }}%)</td>
                    <td>{{ $quiz['attempts'] }}</td>
                    <td>{{ $quiz['average_percent'] !== null ? $quiz['average_percent'].'%' : 'N/A' }}</td>
                    <td>
                        @if($latest)
                            {{ (int) ($latest['score'] ?? 0) }}/{{ (int) ($latest['total'] ?? 0) }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td><a class="btn-primary" href="{{ route('teacher.students.report', $studentId) }}">Open</a></td>
                </tr>
            @empty
                <tr><td colspan="6" class="muted">No students assigned yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
