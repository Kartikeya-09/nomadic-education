<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\School\StoreSchoolRequest;
use App\Http\Requests\School\UpdateSchoolRequest;
use App\Models\School;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = School::query();

        $with = array_filter(array_map('trim', explode(',', (string) $request->query('with', ''))));
        $allowedWith = ['classes', 'headTeacher'];
        $with = array_values(array_intersect($with, $allowedWith));
        if ($with) {
            $query->with($with);
        }

        if ($request->filled('school_code')) {
            $query->where('school_code', $request->string('school_code'));
        }

        if ($request->filled('community_tribe')) {
            $query->where('community_tribe', $request->string('community_tribe'));
        }

        if ($request->filled('current_camp_location')) {
            $query->where('current_camp_location', $request->string('current_camp_location'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->boolean('status'));
        }

        $perPage = (int) $request->query('per_page', 15);
        $perPage = max(1, min($perPage, 100));

        $schools = $query->latest()->paginate($perPage);

        return response()->json($schools);
    }

    public function store(StoreSchoolRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['status'] = $data['status'] ?? true;

        $school = School::create($data);

        return response()->json($school, 201);
    }

    public function show(School $school): JsonResponse
    {
        return response()->json($school);
    }

    public function update(UpdateSchoolRequest $request, School $school): JsonResponse
    {
        $school->fill($request->validated())->save();

        return response()->json($school);
    }

    public function destroy(School $school): JsonResponse
    {
        $school->delete();

        return response()->json(null, 204);
    }
}
