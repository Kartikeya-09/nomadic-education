@extends('teacher.layout')

@section('title', 'Assigned Students')
@section('page_title', 'Assigned Students')
@section('active_nav', 'students')

@section('content')
<div class="card" style="margin-bottom: 14px;">
    <h3 style="margin:0 0 8px;">Live student tracking</h3>
    <div class="muted">You can update class placement and open full student reports from here.</div>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Contact</th>
                <th>Class</th>
                <th>Progress</th>
                <th>Latest Quiz</th>
                <th>Avg Quiz</th>
                <th>Report</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                @php
                    $studentId = (string) $student->getKey();
                    $progress = $progressByStudent[$studentId] ?? ['completed' => 0, 'total' => 0, 'percent' => 0];
                    $quiz = $quizByStudent[$studentId] ?? ['latest' => null, 'average_percent' => null];
                    $latest = $quiz['latest'] ?? null;
                @endphp
                <tr>
                    <td>
                        <strong>{{ $student->name }}</strong>
                        <div class="muted" style="font-size:12px;">ID: {{ $studentId }}</div>
                    </td>
                    <td>{{ $student->email ?? $student->phone ?? $student->guardian_phone ?? 'N/A' }}</td>
                    <td>
                        <form action="{{ route('teacher.students.assign_class', $studentId) }}" method="POST" style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                            @csrf
                            <select name="class_id" style="min-width:210px;">
                                @forelse($classes as $class)
                                    <option value="{{ (string) $class->getKey() }}" {{ (string) ($student->class_id ?? '') === (string) $class->getKey() ? 'selected' : '' }}>
                                        {{ $class->name }}{{ $class->section ? ' - '.$class->section : '' }}
                                    </option>
                                @empty
                                    <option value="">No classes available</option>
                                @endforelse
                            </select>
                            <button class="btn-primary" type="submit" {{ $classes->isEmpty() ? 'disabled' : '' }}>Save</button>
                        </form>
                    </td>
                    <td>
                        <strong>{{ $progress['percent'] }}%</strong>
                        <div class="muted" style="font-size:12px;">{{ $progress['completed'] }}/{{ $progress['total'] }} lessons</div>
                    </td>
                    <td>
                        @if($latest)
                            {{ (int) ($latest['score'] ?? 0) }}/{{ (int) ($latest['total'] ?? 0) }}
                            <div class="muted" style="font-size:12px;">{{ $latest['taken_at'] ?? '' }}</div>
                        @else
                            <span class="muted">No quiz yet</span>
                        @endif
                    </td>
                    <td>
                        @if($quiz['average_percent'] !== null)
                            {{ $quiz['average_percent'] }}%
                        @else
                            <span class="muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        <a class="btn-ghost" href="{{ route('teacher.students.report', $studentId) }}">Open Report</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="muted">No students are assigned to you yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
