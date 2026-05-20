<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lesson\StoreLessonRequest;
use App\Http\Requests\Lesson\UpdateLessonRequest;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Lesson::query();

        $with = array_filter(array_map('trim', explode(',', (string) $request->query('with', ''))));
        $allowedWith = ['class', 'teacher'];
        $with = array_values(array_intersect($with, $allowedWith));
        if ($with) {
            $query->with($with);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->string('class_id'));
        }

        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->string('teacher_id'));
        }

        if ($request->filled('date')) {
            $query->where('date', $request->date('date'));
        }

        $perPage = (int) $request->query('per_page', 15);
        $perPage = max(1, min($perPage, 100));

        $lessons = $query->latest()->paginate($perPage);

        return response()->json($lessons);
    }

    public function store(StoreLessonRequest $request): JsonResponse
    {
        $lesson = Lesson::create($request->validated());

        return response()->json($lesson, 201);
    }

    public function show(Lesson $lesson): JsonResponse
    {
        return response()->json($lesson);
    }

    public function update(UpdateLessonRequest $request, Lesson $lesson): JsonResponse
    {
        $lesson->fill($request->validated())->save();

        return response()->json($lesson);
    }

    public function destroy(Lesson $lesson): JsonResponse
    {
        $lesson->delete();

        return response()->json(null, 204);
    }
}
