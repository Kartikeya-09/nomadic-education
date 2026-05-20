@extends('teacher.layout')

@section('title', 'Worksheets')
@section('page_title', 'Worksheets')
@section('active_nav', 'worksheets')

@section('content')
@php
    $classMap = $classes->mapWithKeys(fn ($class) => [(string) $class->getKey() => trim($class->name . ($class->section ? ' - '.$class->section : ''))])->all();
@endphp
<div class="card" style="margin-bottom: 14px;">
    <h3 style="margin:0 0 8px;">Lesson and worksheet references</h3>
    <div class="muted">Showing lessons from your classes only.</div>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Lesson</th>
                <th>Class</th>
                <th>Date</th>
                <th>Topic</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lessons as $lesson)
                <tr>
                    <td>{{ $lesson->title ?? 'Untitled lesson' }}</td>
                    <td>{{ $classMap[(string) ($lesson->class_id ?? '')] ?? 'Unknown class' }}</td>
                    <td>{{ $lesson->date ? $lesson->date->format('M d, Y') : 'N/A' }}</td>
                    <td>{{ $lesson->topic ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="muted">No lessons available yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
