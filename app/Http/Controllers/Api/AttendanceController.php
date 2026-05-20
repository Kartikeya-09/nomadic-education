<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\StoreAttendanceRequest;
use App\Http\Requests\Attendance\UpdateAttendanceRequest;
use App\Models\Attendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Attendance::query();

        $with = array_filter(array_map('trim', explode(',', (string) $request->query('with', ''))));
        $allowedWith = ['class', 'student', 'marker'];
        $with = array_values(array_intersect($with, $allowedWith));
        if ($with) {
            $query->with($with);
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->string('student_id'));
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->string('class_id'));
        }

        if ($request->filled('date')) {
            $query->where('date', $request->date('date'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('marked_by')) {
            $query->where('marked_by', $request->string('marked_by'));
        }

        $perPage = (int) $request->query('per_page', 15);
        $perPage = max(1, min($perPage, 100));

        $attendance = $query->latest()->paginate($perPage);

        return response()->json($attendance);
    }

    public function store(StoreAttendanceRequest $request): JsonResponse
    {
        $record = Attendance::create($request->validated());

        return response()->json($record, 201);
    }

    public function show(Attendance $attendance): JsonResponse
    {
        return response()->json($attendance);
    }

    public function update(UpdateAttendanceRequest $request, Attendance $attendance): JsonResponse
    {
        $attendance->fill($request->validated())->save();

        return response()->json($attendance);
    }

    public function destroy(Attendance $attendance): JsonResponse
    {
        $attendance->delete();

        return response()->json(null, 204);
    }
}
