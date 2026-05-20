<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\TeacherAssignment;
use MongoDB\BSON\ObjectId;

class AdminController extends Controller
{
    private function resolveUser(string $id): User
    {
        $id = trim($id);

        if (preg_match('/^[a-f0-9]{24}$/i', $id)) {
            try {
                $user = User::where('_id', new \MongoDB\BSON\ObjectId($id))->first();
                if ($user) {
                    return $user;
                }
            } catch (\Throwable $e) {
                // Fallback below if ObjectId parsing fails
            }
        }

        $user = User::where('_id', $id)->first();
        if ($user) {
            return $user;
        }

        return User::findOrFail($id);
    }
    public function dashboard()
    {
        $studentCount = User::role('student')->count();
        if ($studentCount === 0) {
            $studentCount = User::whereNotNull('class_id')->count();
        }

        $teacherCount = User::role('teacher')->count();
        if ($teacherCount === 0) {
            $teacherCount = User::whereNotNull('subject_specialization')->count();
        }

        $campCount = School::where('status', true)->count();
        if ($campCount === 0) {
            $campCount = School::count();
        }

        $stats = [
            "students" => $studentCount,
            "teachers" => $teacherCount,
            "camps" => $campCount,
        ];
        
        $recentUsers = User::with('roles')->latest()->take(8)->get();

        return view("admin.dashboard", compact("stats", "recentUsers"));
    }

    public function users(Request $request)
    {
        $roleFilter = (string) $request->query('role', 'all');
        if (! in_array($roleFilter, ['all', 'student', 'teacher'], true)) {
            $roleFilter = 'all';
        }

        $allUsers = User::with(['roles'])->latest()->get();
        $users = $allUsers;

        if ($roleFilter === 'teacher') {
            $users = $allUsers
                ->filter(function (User $user) {
                    $roleName = $user->roles->first()->name ?? null;
                    return $roleName === 'teacher' || ! empty($user->subject_specialization);
                })
                ->values();
        } elseif ($roleFilter === 'student') {
            $users = $allUsers
                ->filter(function (User $user) {
                    $roleName = $user->roles->first()->name ?? null;
                    if ($roleName === 'student') {
                        return true;
                    }

                    return $roleName === null && empty($user->subject_specialization);
                })
                ->values();
        }

        $summary = [
            'total' => $allUsers->count(),
            'students' => $allUsers->filter(fn (User $u) => ($u->roles->first()->name ?? 'student') === 'student')->count(),
            'teachers' => $allUsers->filter(function (User $u) {
                $roleName = $u->roles->first()->name ?? null;
                return $roleName === 'teacher' || ! empty($u->subject_specialization);
            })->count(),
        ];

        $classes = SchoolClass::with('teacher')->get();
        $teachers = User::role('teacher')->latest()->get();
        if ($teachers->isEmpty()) {
            $teachers = User::whereNotNull('subject_specialization')->latest()->get();
        }
        $assignments = TeacherAssignment::all()
            ->mapWithKeys(fn ($row) => [(string) $row->student_id => (string) $row->teacher_id])
            ->all();
        return view("admin.users", compact("users", "classes", "teachers", "assignments", "summary", "roleFilter"));
    }

    public function createUser()
    {
        return view("admin.users_create");
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'login' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string'
        ]);

        $user = User::create([
            'name' => $request->name,
            'login' => $request->login,
            'password' => \Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.users')->with('success', 'User added successfully.');
    }

    public function assignClass(Request $request, string $user)
    {
        $request->validate([
            'class_id' => 'required|string',
        ]);

        $user = $this->resolveUser($user);

        $class = SchoolClass::findOrFail($request->class_id);

        $user->class_id = (string) $class->getKey();
        if (! empty($class->school_id)) {
            $school = School::find($class->school_id);
            if ($school?->current_camp_location) {
                $user->current_camp_location = $school->current_camp_location;
            }
        }
        $user->save();

        return redirect()->route('admin.users')->with('success', "Assigned class {$class->name} to {$user->name}.");
    }

    public function assignTeacher(Request $request, string $user)
    {
        $request->validate([
            'teacher_id' => 'nullable|string',
        ]);

        $teacherId = $request->input('teacher_id');
        $user = $this->resolveUser($user);
        return $this->applyTeacherAssignment($request, $user, $teacherId);
    }

    public function assignTeacherById(Request $request)
    {
        $request->validate([
            'user_id' => 'required|string',
            'teacher_id' => 'nullable|string',
        ]);

        $user = $this->resolveUser($request->input('user_id'));
        $teacherId = $request->input('teacher_id');

        return $this->applyTeacherAssignment($request, $user, $teacherId);
    }

    private function applyTeacherAssignment(Request $request, User $user, ?string $teacherId)
    {
        if (empty($teacherId)) {
            $deleted = TeacherAssignment::where('student_id', (string) $user->getKey())->delete();
            $message = $deleted
                ? "Removed teacher assignment for {$user->name}."
                : "No teacher assigned for {$user->name}.";

            if ($request->expectsJson()) {
                return response()->json(['message' => $message, 'teacher_id' => null]);
            }

            return redirect()->route('admin.users')->with('success', $message);
        }

        $teacher = $this->resolveUser($teacherId);
        $isTeacher = $teacher->hasRole('teacher') || ! empty($teacher->subject_specialization);
        if (! $isTeacher) {
            $error = 'Selected user is not a teacher.';
            if ($request->expectsJson()) {
                return response()->json(['message' => $error], 422);
            }

            return back()->withErrors([
                'teacher_id' => $error,
            ]);
        }

        $current = TeacherAssignment::where('student_id', (string) $user->getKey())->first();
        $currentTeacherId = (string) ($current?->teacher_id ?? '');
        if ($currentTeacherId !== (string) $teacher->getKey()) {
            $assignedCount = TeacherAssignment::where('teacher_id', (string) $teacher->getKey())->count();
            if ($assignedCount >= 4) {
                $error = "{$teacher->name} already has 4 students assigned.";
                if ($request->expectsJson()) {
                    return response()->json(['message' => $error], 422);
                }

                return back()->withErrors([
                    'teacher_id' => $error,
                ]);
            }
        }

        TeacherAssignment::updateOrCreate(
            ['student_id' => (string) $user->getKey()],
            ['teacher_id' => (string) $teacher->getKey()]
        );

        $message = "Assigned {$teacher->name} to {$user->name}.";
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'teacher_id' => (string) $teacher->getKey(),
                'teacher_name' => $teacher->name,
            ]);
        }

        return redirect()->route('admin.users')->with('success', $message);
    }

    public function resetPassword(Request $request, string $user)
    {
        $request->validate([
            'new_password' => 'required|string|min:6'
        ]);

        $user = $this->resolveUser($user);

        $user->update([
            'password' => \Hash::make($request->new_password)
        ]);

        return redirect()->route('admin.users')->with('success', "Password for {$user->name} has been reset successfully.");
    }
}
