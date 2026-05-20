<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreTeacherRequest;
use App\Http\Requests\Teacher\UpdateTeacherRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = User::role('teacher');

        $with = array_filter(array_map('trim', explode(',', (string) $request->query('with', ''))));
        $allowedWith = ['classesTaught', 'lessonsTaught', 'assessmentsTaught', 'attendanceMarked', 'schoolsHeaded'];
        $with = array_values(array_intersect($with, $allowedWith));
        if ($with) {
            $query->with($with);
        }

        $perPage = (int) $request->query('per_page', 15);
        $perPage = max(1, min($perPage, 100));

        $teachers = $query->latest()->paginate($perPage);

        return response()->json($teachers);
    }

    public function store(StoreTeacherRequest $request): JsonResponse
    {
        $data = $request->validated();

        $data['preferred_language'] = $data['preferred_language'] ?? 'Gojri';
        $data['status'] = $data['status'] ?? true;
        $data['is_volunteer'] = $data['is_volunteer'] ?? false;
        $data['password'] = Hash::make($data['password']);

        $teacher = User::create($data);
        $teacher->assignRole('teacher');

        return response()->json($teacher, 201);
    }

    public function show(User $teacher): JsonResponse
    {
        if (! $teacher->hasRole('teacher')) {
            abort(404);
        }

        return response()->json($teacher);
    }

    public function update(UpdateTeacherRequest $request, User $teacher): JsonResponse
    {
        if (! $teacher->hasRole('teacher')) {
            abort(404);
        }

        $data = $request->validated();

        if (array_key_exists('password', $data)) {
            $data['password'] = Hash::make($data['password']);
        }

        $teacher->fill($data)->save();

        return response()->json($teacher);
    }

    public function destroy(User $teacher): JsonResponse
    {
        if (! $teacher->hasRole('teacher')) {
            abort(404);
        }

        $teacher->delete();

        return response()->json(null, 204);
    }
}
