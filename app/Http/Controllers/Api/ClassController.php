<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Class\StoreClassRequest;
use App\Http\Requests\Class\UpdateClassRequest;
use App\Models\SchoolClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = SchoolClass::query();

        $with = array_filter(array_map('trim', explode(',', (string) $request->query('with', ''))));
        $allowedWith = ['school', 'teacher', 'lessons', 'assessments', 'attendance', 'content'];
        $with = array_values(array_intersect($with, $allowedWith));
        if ($with) {
            $query->with($with);
        }

        if ($request->filled('school_id')) {
            $query->where('school_id', $request->string('school_id'));
        }

        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->string('teacher_id'));
        }

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->string('academic_year'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->boolean('status'));
        }

        $perPage = (int) $request->query('per_page', 15);
        $perPage = max(1, min($perPage, 100));

        $classes = $query->latest()->paginate($perPage);

        return response()->json($classes);
    }

    public function store(StoreClassRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['status'] = $data['status'] ?? true;

        $class = SchoolClass::create($data);

        return response()->json($class, 201);
    }

    public function show(SchoolClass $class): JsonResponse
    {
        return response()->json($class);
    }

    public function update(UpdateClassRequest $request, SchoolClass $class): JsonResponse
    {
        $class->fill($request->validated())->save();

        return response()->json($class);
    }

    public function destroy(SchoolClass $class): JsonResponse
    {
        $class->delete();

        return response()->json(null, 204);
    }
}
