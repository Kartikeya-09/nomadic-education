@extends('student.layout')

@section('title', 'Achievements')
@section('page_title', 'Achievements')
@section('active_nav', 'achievements')

@section('content')
<div class="card">
    <h3 style="margin-top:0;">My Achievements</h3>
    <div class="grid" style="grid-template-columns: 1fr; margin-top: 10px;">
        @forelse($achievements as $achievement)
            <div class="card" style="padding:12px;">
                <strong>{{ $achievement }}</strong>
            </div>
        @empty
            <div class="muted">Complete lessons and quizzes to unlock achievements.</div>
        @endforelse
    </div>
</div>
@endsection
