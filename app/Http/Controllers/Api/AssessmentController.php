<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Assessment\StoreAssessmentRequest;
use App\Http\Requests\Assessment\UpdateAssessmentRequest;
use App\Models\Assessment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Assessment::query();

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

        $assessments = $query->latest()->paginate($perPage);

        return response()->json($assessments);
    }

    public function store(StoreAssessmentRequest $request): JsonResponse
    {
        $assessment = Assessment::create($request->validated());

        return response()->json($assessment, 201);
    }

    public function show(Assessment $assessment): JsonResponse
    {
        return response()->json($assessment);
    }

    public function update(UpdateAssessmentRequest $request, Assessment $assessment): JsonResponse
    {
        $assessment->fill($request->validated())->save();

        return response()->json($assessment);
    }

    public function destroy(Assessment $assessment): JsonResponse
    {
        $assessment->delete();

        return response()->json(null, 204);
    }
}
