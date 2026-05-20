<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = User::role('student');

        $with = array_filter(array_map('trim', explode(',', (string) $request->query('with', ''))));
        $allowedWith = ['attendanceRecords', 'schoolClass'];
        $with = array_values(array_intersect($with, $allowedWith));
        if ($with) {
            $query->with($with);
        }

        $perPage = (int) $request->query('per_page', 15);
        $perPage = max(1, min($perPage, 100));

        $students = $query->latest()->paginate($perPage);

        return response()->json($students);
    }

    public function store(StoreStudentRequest $request): JsonResponse
    {
        $data = $request->validated();

        $data['email'] = $data['email'] ?? null;
        $data['preferred_language'] = $data['preferred_language'] ?? 'Gojri';
        $data['status'] = $data['status'] ?? true;
        $data['password'] = Hash::make($data['password']);

        $student = User::create($data);
        $student->assignRole('student');

        return response()->json($student, 201);
    }

    public function show(User $student): JsonResponse
    {
        if (! $student->hasRole('student')) {
            abort(404);
        }

        return response()->json($student);
    }

    public function update(UpdateStudentRequest $request, User $student): JsonResponse
    {
        if (! $student->hasRole('student')) {
            abort(404);
        }

        $data = $request->validated();

        if (array_key_exists('password', $data)) {
            $data['password'] = Hash::make($data['password']);
        }

        $student->fill($data)->save();

        return response()->json($student);
    }

    public function destroy(User $student): JsonResponse
    {
        if (! $student->hasRole('student')) {
            abort(404);
        }

        $student->delete();

        return response()->json(null, 204);
    }
}
