<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Models\User;
use App\Models\Lesson;
use App\Models\SchoolClass;
use App\Models\TeacherAssignment;
use MongoDB\BSON\ObjectId;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $teacher = Auth::user();
        $payload = $this->buildDashboardPayload($teacher);

        return view("teacher.dashboard", [
            'studentCount' => $payload['studentCount'],
            'classCount' => $payload['classCount'],
            'avgProgress' => $payload['avgProgress'],
            'avgQuiz' => $payload['avgQuiz'],
            'pendingLessons' => $payload['pendingLessons'],
            'topStudents' => $payload['topStudents'],
            'recentActivity' => $payload['recentActivity'],
            'classes' => $payload['classes'],
        ]);
    }

    public function dashboardMetrics()
    {
        $teacher = Auth::user();
        return response()->json($this->buildDashboardPayload($teacher));
    }

    public function students()
    {
        $teacher = Auth::user();
        $students = $this->assignedStudents($teacher);
        $classes = $this->teacherClasses($teacher);
        $metrics = $this->buildStudentMetrics($students);

        return view("teacher.students", [
            'students' => $students,
            'classes' => $classes,
            'progressByStudent' => $metrics['progressByStudent'],
            'quizByStudent' => $metrics['quizByStudent'],
        ]);
    }

    public function classes()
    {
        $teacher = Auth::user();
        $classes = $this->teacherClasses($teacher);
        $students = $this->assignedStudents($teacher);
        $metrics = $this->buildStudentMetrics($students);

        $classStats = [];
        foreach ($classes as $class) {
            $classId = (string) $class->getKey();
            $classStudents = $students->filter(fn (User $student) => (string) ($student->class_id ?? '') === $classId);
            $count = $classStudents->count();
            $avgProgress = $count > 0
                ? (float) round($classStudents->avg(fn (User $student) => (float) ($metrics['progressByStudent'][(string) $student->getKey()]['percent'] ?? 0)), 1)
                : 0.0;

            $classStats[$classId] = [
                'students' => $count,
                'avg_progress' => $avgProgress,
            ];
        }

        return view("teacher.classes", compact("classes", "classStats"));
    }

    public function reports()
    {
        $teacher = Auth::user();
        $students = $this->assignedStudents($teacher);
        $metrics = $this->buildStudentMetrics($students);

        return view("teacher.reports", [
            'students' => $students,
            'progressByStudent' => $metrics['progressByStudent'],
            'quizByStudent' => $metrics['quizByStudent'],
            'summary' => $metrics['summary'],
        ]);
    }

    public function studentReport(User $student)
    {
        $teacher = Auth::user();
        $this->ensureAssignedStudent($teacher, $student);

        $metrics = $this->buildStudentMetrics(collect([$student]));
        $studentId = (string) $student->getKey();
        $progress = $metrics['progressByStudent'][$studentId] ?? ['completed' => 0, 'total' => 0, 'percent' => 0];
        $quiz = $metrics['quizByStudent'][$studentId] ?? ['latest' => null, 'average_percent' => null, 'attempts' => 0];

        $classes = $this->teacherClasses($teacher);
        $currentClass = ! empty($student->class_id)
            ? SchoolClass::find((string) $student->class_id)
            : null;

        $lessons = empty($student->class_id)
            ? collect()
            : Lesson::where('class_id', (string) $student->class_id)->get(['_id', 'title']);

        $completedIds = array_map('strval', (array) ($student->completed_lessons ?? []));
        $completedLessons = $lessons
            ->filter(fn (Lesson $lesson) => in_array((string) $lesson->getKey(), $completedIds, true))
            ->values();

        $classNameMap = SchoolClass::whereIn('_id', collect((array) ($student->quiz_results ?? []))
            ->pluck('class_id')
            ->filter()
            ->map(fn ($id) => (string) $id)
            ->unique()
            ->values()
            ->all())
            ->get(['_id', 'name', 'section'])
            ->mapWithKeys(fn (SchoolClass $class) => [
                (string) $class->getKey() => trim($class->name . ($class->section ? ' - ' . $class->section : '')),
            ])
            ->all();

        $quizHistory = collect((array) ($student->quiz_results ?? []))
            ->filter(fn ($row) => is_array($row))
            ->map(function ($row) use ($classNameMap) {
                $score = (int) ($row['score'] ?? 0);
                $total = (int) ($row['total'] ?? 0);
                $classId = (string) ($row['class_id'] ?? '');
                $row['percent'] = $total > 0 ? (int) round(($score / $total) * 100) : 0;
                $row['class_name'] = $classNameMap[$classId] ?? 'Unknown class';
                return $row;
            })
            ->sortByDesc(fn ($row) => strtotime((string) ($row['taken_at'] ?? '1970-01-01 00:00:00')))
            ->values();

        return view("teacher.student_report", compact(
            "student",
            "classes",
            "currentClass",
            "progress",
            "quiz",
            "quizHistory",
            "completedLessons"
        ));
    }

    public function assignClass(Request $request, User $student)
    {
        $request->validate([
            'class_id' => 'required|string',
        ]);

        $teacher = Auth::user();
        $this->ensureAssignedStudent($teacher, $student);

        $class = $this->resolveClass((string) $request->input('class_id'));
        if (! $class) {
            return back()->withErrors([
                'class_id' => 'Selected class was not found.',
            ]);
        }

        $allowedClassIds = $this->teacherClasses($teacher)
            ->map(fn (SchoolClass $item) => (string) $item->getKey())
            ->all();

        if (! in_array((string) $class->getKey(), $allowedClassIds, true)) {
            return back()->withErrors([
                'class_id' => 'You can only assign classes available in your class list.',
            ]);
        }

        $student->class_id = (string) $class->getKey();
        $student->save();

        return back()->with('success', "Assigned {$class->name} to {$student->name}.");
    }

    public function worksheets()
    {
        $teacher = Auth::user();
        $classes = $this->teacherClasses($teacher);
        $classIds = $classes->pluck('_id')->map(fn ($id) => (string) $id)->all();

        $lessons = empty($classIds)
            ? collect()
            : Lesson::whereIn('class_id', $classIds)->orderBy('date', 'desc')->get();

        return view("teacher.worksheets", compact("lessons", "classes"));
    }

    private function buildDashboardPayload(User $teacher): array
    {
        $students = $this->assignedStudents($teacher);
        $classes = $this->teacherClasses($teacher);
        $metrics = $this->buildStudentMetrics($students);

        return [
            'studentCount' => $students->count(),
            'classCount' => $classes->count(),
            'avgProgress' => $metrics['summary']['avg_progress'],
            'avgQuiz' => $metrics['summary']['avg_quiz'],
            'pendingLessons' => $metrics['summary']['pending_lessons'],
            'topStudents' => collect($metrics['progressByStudent'])
                ->map(function ($row, $studentId) use ($students, $metrics) {
                    $student = $students->first(fn (User $item) => (string) $item->getKey() === (string) $studentId);
                    if (! $student) {
                        return null;
                    }

                    return [
                        'id' => (string) $student->getKey(),
                        'name' => $student->name,
                        'progress' => $row['percent'],
                        'quiz' => $metrics['quizByStudent'][$studentId]['latest_percent'] ?? null,
                    ];
                })
                ->filter()
                ->sortByDesc('progress')
                ->take(5)
                ->values()
                ->all(),
            'recentActivity' => collect($metrics['recentActivity'])->take(6)->values()->all(),
            'classes' => $classes->map(fn (SchoolClass $class) => [
                'id' => (string) $class->getKey(),
                'name' => trim($class->name . ($class->section ? ' - ' . $class->section : '')),
            ])->values()->all(),
            'updated_at' => now()->toDateTimeString(),
        ];
    }

    private function teacherClasses(User $teacher): Collection
    {
        $classes = SchoolClass::where('teacher_id', (string) $teacher->getKey())
            ->orderBy('name')
            ->get();

        if ($classes->isNotEmpty()) {
            return $classes;
        }

        $students = $this->assignedStudents($teacher);
        $classIds = $students->pluck('class_id')
            ->filter()
            ->map(fn ($id) => (string) $id)
            ->unique()
            ->values();

        if ($classIds->isNotEmpty()) {
            return SchoolClass::whereIn('_id', $classIds->all())
                ->orderBy('name')
                ->get();
        }

        return SchoolClass::orderBy('name')->get();
    }

    private function resolveClass(string $classId): ?SchoolClass
    {
        $classId = trim($classId);
        if ($classId === '') {
            return null;
        }

        $class = SchoolClass::where('_id', $classId)->first();
        if ($class) {
            return $class;
        }

        if (preg_match('/^[a-f0-9]{24}$/i', $classId)) {
            try {
                return SchoolClass::where('_id', new ObjectId($classId))->first();
            } catch (\Throwable $e) {
                return null;
            }
        }

        return null;
    }

    private function assignedStudents(User $teacher): Collection
    {
        $studentIds = TeacherAssignment::where('teacher_id', (string) $teacher->getKey())
            ->pluck('student_id')
            ->map(fn ($id) => (string) $id)
            ->filter()
            ->unique()
            ->values();

        if ($studentIds->isEmpty()) {
            return collect();
        }

        $queryIds = $studentIds
            ->map(function (string $id) {
                if (preg_match('/^[a-f0-9]{24}$/i', $id)) {
                    try {
                        return new ObjectId($id);
                    } catch (\Throwable $e) {
                        return $id;
                    }
                }

                return $id;
            })
            ->all();

        return User::with('roles')
            ->whereIn('_id', $queryIds)
            ->get()
            ->filter(fn (User $user) => $user->hasRole('student') || ! empty($user->class_id))
            ->values();
    }

    private function ensureAssignedStudent(User $teacher, User $student): void
    {
        $isAssigned = TeacherAssignment::where('teacher_id', (string) $teacher->getKey())
            ->where('student_id', (string) $student->getKey())
            ->exists();

        if (! $isAssigned) {
            abort(403);
        }
    }

    private function buildStudentMetrics(Collection $students): array
    {
        $classIds = $students->pluck('class_id')
            ->filter()
            ->map(fn ($id) => (string) $id)
            ->unique()
            ->values()
            ->all();

        $lessonIdsByClass = empty($classIds)
            ? []
            : Lesson::whereIn('class_id', $classIds)
                ->get(['_id', 'class_id'])
                ->groupBy('class_id')
                ->map(fn ($items) => $items->pluck('_id')->map(fn ($id) => (string) $id)->all())
                ->all();

        $progressByStudent = [];
        $quizByStudent = [];
        $recentActivity = [];

        $progressTotal = 0;
        $quizTotal = 0;
        $quizStudentCount = 0;
        $totalLessons = 0;
        $completedLessons = 0;

        foreach ($students as $student) {
            $studentId = (string) $student->getKey();
            $classId = (string) ($student->class_id ?? '');
            $lessonIds = $lessonIdsByClass[$classId] ?? [];
            $total = count($lessonIds);
            $completed = array_map('strval', (array) ($student->completed_lessons ?? []));
            $validCompleted = $lessonIds ? array_values(array_intersect($completed, $lessonIds)) : [];
            $completedCount = count($validCompleted);
            $percent = $total > 0 ? (int) round(($completedCount / $total) * 100) : 0;

            $progressByStudent[$studentId] = [
                'completed' => $completedCount,
                'total' => $total,
                'percent' => $percent,
            ];

            $progressTotal += $percent;
            $totalLessons += $total;
            $completedLessons += $completedCount;

            $results = collect((array) ($student->quiz_results ?? []))
                ->filter(fn ($row) => is_array($row))
                ->values();

            $latest = $results
                ->sortByDesc(fn ($row) => strtotime((string) ($row['taken_at'] ?? '1970-01-01 00:00:00')))
                ->first();

            $latestPercent = null;
            if (is_array($latest)) {
                $latestTotal = (int) ($latest['total'] ?? 0);
                $latestScore = (int) ($latest['score'] ?? 0);
                $latestPercent = $latestTotal > 0 ? (int) round(($latestScore / $latestTotal) * 100) : 0;
            }

            $averagePercent = $results->isNotEmpty()
                ? (float) round($results->avg(function ($row) {
                    $rowTotal = (int) ($row['total'] ?? 0);
                    $rowScore = (int) ($row['score'] ?? 0);
                    return $rowTotal > 0 ? ($rowScore / $rowTotal) * 100 : 0;
                }), 1)
                : null;

            if ($averagePercent !== null) {
                $quizTotal += $averagePercent;
                $quizStudentCount++;
            }

            $quizByStudent[$studentId] = [
                'latest' => $latest,
                'latest_percent' => $latestPercent,
                'average_percent' => $averagePercent,
                'attempts' => $results->count(),
            ];

            if (is_array($latest) && ! empty($latest['taken_at'])) {
                $recentActivity[] = [
                    'student' => $student->name,
                    'message' => sprintf(
                        'scored %d/%d in quiz',
                        (int) ($latest['score'] ?? 0),
                        (int) ($latest['total'] ?? 0)
                    ),
                    'time' => (string) $latest['taken_at'],
                ];
            }
        }

        usort($recentActivity, fn ($a, $b) => strtotime((string) $b['time']) <=> strtotime((string) $a['time']));

        $studentCount = $students->count();
        $avgProgress = $studentCount > 0 ? (float) round($progressTotal / $studentCount, 1) : 0.0;
        $avgQuiz = $quizStudentCount > 0 ? (float) round($quizTotal / $quizStudentCount, 1) : 0.0;
        $pendingLessons = max($totalLessons - $completedLessons, 0);

        return [
            'progressByStudent' => $progressByStudent,
            'quizByStudent' => $quizByStudent,
            'recentActivity' => $recentActivity,
            'summary' => [
                'avg_progress' => $avgProgress,
                'avg_quiz' => $avgQuiz,
                'pending_lessons' => $pendingLessons,
            ],
        ];
    }
}
