<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolClass;
use App\Models\Lesson;
use App\Models\Content;
use App\Models\QuizQuestion;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $classesQuery = SchoolClass::query();
        if (! empty($user->class_id)) {
            $classesQuery->where('_id', $user->class_id);
        }

        $classes = $classesQuery->get();

        $completedLessons = array_map('strval', (array) ($user->completed_lessons ?? []));
        $completedCount = 0;
        $totalLessons = 0;
        $pendingLessons = 0;

        $lessonCounts = collect();

        if ($classes->isNotEmpty()) {
            $classIds = $classes->pluck('_id')->map(fn ($id) => (string) $id)->all();
            $lessonIds = Lesson::whereIn('class_id', $classIds)
                ->pluck('_id')
                ->map(fn ($val) => (string) $val)
                ->all();
            $totalLessons = count($lessonIds);
            $validCompleted = array_values(array_intersect($completedLessons, $lessonIds));
            $completedCount = count($validCompleted);
            $lessonCounts = Lesson::whereIn('class_id', $classIds)
                ->get(['class_id'])
                ->groupBy('class_id')
                ->map(fn ($items) => $items->count());
        }

        $pendingLessons = max($totalLessons - $completedCount, 0);
        $notificationsCount = $pendingLessons;

        $progressPercent = $totalLessons > 0
            ? (int) round(($completedCount / $totalLessons) * 100)
            : 0;

        $achievements = (array) ($user->achievements ?? []);
        $quizResults = collect((array) ($user->quiz_results ?? []))
            ->filter(fn ($row) => is_array($row))
            ->values()
            ->all();
        $latestQuiz = null;
        $quizCount = count($quizResults);
        $avgQuizPercent = 0;
        if ($quizCount > 0) {
            $sorted = collect($quizResults)
                ->sortByDesc(fn ($row) => $row['taken_at'] ?? '')
                ->values()
                ->all();
            $latestQuiz = $sorted[0];
            $latestTotal = (int) ($latestQuiz['total'] ?? 0);
            $latestScore = (int) ($latestQuiz['score'] ?? 0);
            $latestQuiz['percent'] = $latestTotal > 0
                ? (int) round(($latestScore / $latestTotal) * 100)
                : 0;

            $percentSum = 0;
            foreach ($quizResults as $quiz) {
                $total = (int) ($quiz['total'] ?? 0);
                $score = (int) ($quiz['score'] ?? 0);
                $percentSum += $total > 0 ? (int) round(($score / $total) * 100) : 0;
            }
            $avgQuizPercent = (int) round($percentSum / $quizCount);
        }

        return view("student.dashboard", compact(
            "classes",
            "completedCount",
            "totalLessons",
            "progressPercent",
            "lessonCounts",
            "pendingLessons",
            "notificationsCount",
            "achievements",
            "latestQuiz",
            "quizCount",
            "avgQuizPercent"
        ));
    }

    public function courses()
    {
        $user = Auth::user();

        $classesQuery = SchoolClass::query();
        if (! empty($user->class_id)) {
            $classesQuery->where('_id', $user->class_id);
        }

        $classes = $classesQuery->get();

        return view('student.courses', compact('classes'));
    }

    public function progress()
    {
        $user = Auth::user();
        $completedLessons = array_map('strval', (array) ($user->completed_lessons ?? []));
        $completedCount = 0;

        $classesQuery = SchoolClass::query();
        if (! empty($user->class_id)) {
            $classesQuery->where('_id', $user->class_id);
        }
        $classes = $classesQuery->get();

        $classIds = $classes->pluck('_id')->map(fn ($id) => (string) $id)->all();
        $lessonIds = empty($classIds)
            ? []
            : Lesson::whereIn('class_id', $classIds)
                ->pluck('_id')
                ->map(fn ($val) => (string) $val)
                ->all();
        $totalLessons = count($lessonIds);
        $validCompleted = array_values(array_intersect($completedLessons, $lessonIds));
        $completedCount = count($validCompleted);

        $progressPercent = $totalLessons > 0
            ? (int) round(($completedCount / $totalLessons) * 100)
            : 0;

        return view('student.progress', compact('completedCount', 'totalLessons', 'progressPercent'));
    }

    public function achievements()
    {
        $achievements = (array) (Auth::user()->achievements ?? []);
        return view('student.achievements', compact('achievements'));
    }

    public function showClass(string $id)
    {
        $user = Auth::user();

        $classQuery = SchoolClass::with('lessons');
        if (! empty($user->class_id)) {
            $classQuery->where('_id', $user->class_id);
        }

        $class = $classQuery->findOrFail($id);

        $completedLessons = array_map('strval', (array) ($user->completed_lessons ?? []));

        $lessonIds = $class->lessons->pluck('_id')->map(fn ($val) => (string) $val)->all();
        $classCompleted = ! empty($lessonIds)
            && count(array_diff($lessonIds, $completedLessons)) === 0;

        $quizResults = (array) ($user->quiz_results ?? []);
        $classQuiz = collect($quizResults)->first(fn ($row) => ($row['class_id'] ?? null) === (string) $class->_id);

        return view("student.class", compact("class", "completedLessons", "classCompleted", "classQuiz"));
    }

    public function showLesson(string $id)
    {
        $lesson = Lesson::findOrFail($id);
        $videoContent = Content::where('lesson_id', (string) $lesson->_id)
            ->where('type', 'video')
            ->whereNotNull('file_url')
            ->first();
        $completedLessons = array_map('strval', (array) (Auth::user()->completed_lessons ?? []));
        $isCompleted = in_array((string) $lesson->_id, $completedLessons, true);

        return view("student.lesson", compact("lesson", "isCompleted", "videoContent"));
    }

    public function markComplete(string $id, Request $request)
    {
        $user = Auth::user();
        $completed = array_map('strval', (array) ($user->completed_lessons ?? []));
        $completed[] = (string) $id;

        $user->completed_lessons = array_values(array_unique($completed));
        $user->save();

        $lesson = Lesson::find($id);
        if ($lesson) {
            $classLessons = Lesson::where('class_id', (string) $lesson->class_id)
                ->pluck('_id')
                ->map(fn ($val) => (string) $val)
                ->all();
            $allCompleted = ! empty($classLessons)
                && count(array_diff($classLessons, $user->completed_lessons)) === 0;

            if ($allCompleted) {
                $achievement = "Course Completed: " . ($lesson->class_id ?? 'Class');
                $achievements = (array) ($user->achievements ?? []);
                $achievements[] = $achievement;
                $user->achievements = array_values(array_unique($achievements));
                $user->save();
            }
        }

        return back()->with('status', 'Lesson marked as complete.');
    }

    public function showQuiz(string $classId)
    {
        $user = Auth::user();

        $class = SchoolClass::with('lessons')->findOrFail($classId);
        if (! empty($user->class_id) && (string) $user->class_id !== (string) $class->getKey()) {
            abort(403);
        }
        $lessonIds = $class->lessons->pluck('_id')->map(fn ($val) => (string) $val)->all();
        $completedLessons = array_map('strval', (array) ($user->completed_lessons ?? []));

        $classCompleted = ! empty($lessonIds)
            && count(array_diff($lessonIds, $completedLessons)) === 0;

        if (! $classCompleted) {
            return redirect()->route('student.class', $classId)
                ->with('status', 'Complete all lessons to unlock the quiz.');
        }

        $questions = QuizQuestion::where('class_id', (string) $class->_id)->get();

        return view('student.quiz', compact('class', 'questions'));
    }

    public function submitQuiz(string $classId, Request $request)
    {
        $user = Auth::user();
        $class = SchoolClass::with('lessons')->findOrFail($classId);
        if (! empty($user->class_id) && (string) $user->class_id !== (string) $class->getKey()) {
            abort(403);
        }

        $lessonIds = $class->lessons->pluck('_id')->map(fn ($val) => (string) $val)->all();
        $completedLessons = array_map('strval', (array) ($user->completed_lessons ?? []));
        $classCompleted = ! empty($lessonIds)
            && count(array_diff($lessonIds, $completedLessons)) === 0;
        if (! $classCompleted) {
            return redirect()->route('student.class', $classId)
                ->with('status', 'Complete all lessons to unlock the quiz.');
        }

        $questions = QuizQuestion::where('class_id', (string) $class->_id)->get();
        $total = $questions->count();
        $score = 0;
        $answers = (array) $request->input('answer', []);

        foreach ($questions as $question) {
            $answer = $answers[(string) $question->_id] ?? null;
            if (! is_numeric($answer)) {
                continue;
            }

            $answerIndex = (int) $answer;
            $optionsCount = count((array) ($question->options ?? []));
            if ($answerIndex < 0 || $answerIndex >= $optionsCount) {
                continue;
            }

            if ($answerIndex === (int) $question->correct_index) {
                $score++;
            }
        }

        $results = (array) ($user->quiz_results ?? []);
        $results = array_filter($results, fn ($row) => ($row['class_id'] ?? null) !== (string) $class->_id);
        $results[] = [
            'class_id' => (string) $class->_id,
            'score' => $score,
            'total' => $total,
            'taken_at' => now()->toDateTimeString(),
        ];
        $user->quiz_results = array_values($results);

        $achievements = (array) ($user->achievements ?? []);
        $achievements[] = "Quiz Completed: " . ($class->name ?? 'Class');
        $user->achievements = array_values(array_unique($achievements));

        $user->save();

        return redirect()->route('student.class', $classId)
            ->with('status', "Quiz completed. Score: {$score}/{$total}.");
    }
}
