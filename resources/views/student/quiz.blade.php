@extends('student.layout')

@section('title', 'Class Quiz')
@section('page_title', 'Class Quiz')
@section('active_nav', 'courses')

@section('content')
<form action="{{ route('student.quiz.submit', $class->_id) }}" method="POST">
    @csrf
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:8px; margin-bottom:10px;">
            <h3 style="margin:0;">{{ $class->name ?? 'Class' }} Quiz</h3>
            <a class="btn-ghost" href="{{ route('student.class', $class->_id) }}">Back to Class</a>
        </div>

        <div class="grid" style="grid-template-columns: 1fr;">
            @forelse($questions as $question)
                <div class="card" style="padding:12px;">
                    <h4 style="margin:0 0 8px;">{{ $loop->iteration }}. {{ $question->question }}</h4>
                    @foreach((array) $question->options as $index => $option)
                        <label style="display:flex; gap:8px; align-items:flex-start; margin-bottom:6px;">
                            <input type="radio" name="answer[{{ $question->_id }}]" value="{{ $index }}" required>
                            <span>{{ $option }}</span>
                        </label>
                    @endforeach
                </div>
            @empty
                <div class="muted">No quiz questions available for this class.</div>
            @endforelse
        </div>

        <div style="margin-top:12px;">
            <button type="submit" class="btn-primary" {{ $questions->isEmpty() ? 'disabled' : '' }}>Submit Quiz</button>
        </div>
    </div>
</form>
@endsection
