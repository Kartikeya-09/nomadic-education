<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        h1 { font-size: 18px; margin: 0 0 8px; }
        .meta { margin-bottom: 12px; }
        .meta div { margin-bottom: 2px; }
        .instructions { margin: 10px 0 12px; }
        .question { margin: 8px 0; }
        .marks { float: right; font-size: 11px; color: #555; }
        .divider { border-bottom: 1px solid #ddd; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>

    <div class="meta">
        @if(!empty($class_name))
            <div><strong>Class:</strong> {{ $class_name }}</div>
        @endif
        @if(!empty($teacher_name))
            <div><strong>Teacher:</strong> {{ $teacher_name }}</div>
        @endif
        @if(!empty($date))
            <div><strong>Date:</strong> {{ \Illuminate\Support\Carbon::parse($date)->toDateString() }}</div>
        @endif
    </div>

    @if(!empty($instructions))
        <div class="instructions">
            <strong>Instructions:</strong> {{ $instructions }}
        </div>
    @endif

    <div class="divider"></div>

    @foreach($questions as $index => $question)
        <div class="question">
            <span>{{ $index + 1 }}. {{ $question['text'] }}</span>
            @if(!empty($question['marks']))
                <span class="marks">Marks: {{ $question['marks'] }}</span>
            @endif
        </div>
    @endforeach
</body>
</html>
