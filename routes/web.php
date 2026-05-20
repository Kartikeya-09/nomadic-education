<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\RegisterController;

Route::get("/", function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->hasRole("admin")) return redirect()->route("admin.dashboard");
        if ($user->hasRole("teacher")) return redirect()->route("teacher.dashboard");
        if ($user->hasRole("student")) return redirect()->route("student.dashboard");
        if ($user->hasRole("parent")) return redirect()->route("parent.dashboard");
    }
    return view("welcome");
});

// Registration Routes
Route::get("/register", [RegisterController::class, "showRegistrationForm"])->name("register");
Route::post("/register", [RegisterController::class, "register"]);

// Admin Routes
Route::middleware(["auth", "role:admin"])->prefix("admin")->name("admin.")->group(function () {
    Route::get("/dashboard", [AdminController::class, "dashboard"])->name("dashboard");
    Route::get("/users", [AdminController::class, "users"])->name("users");
    Route::get("/users/create", [AdminController::class, "createUser"])->name("users.create");
    Route::post("/users", [AdminController::class, "storeUser"])->name("users.store");
    Route::post("/users/{user}/reset-password", [AdminController::class, "resetPassword"])->name("users.reset_password");
    Route::post("/users/{user}/assign-class", [AdminController::class, "assignClass"])->name("users.assign_class");
    Route::post("/users/{user}/assign-teacher", [AdminController::class, "assignTeacher"])->name("users.assign_teacher");
    Route::post("/assign-teacher", [AdminController::class, "assignTeacherById"])->name("users.assign_teacher_simple");
});

// Teacher Routes
Route::middleware(["auth", "role:teacher"])->prefix("teacher")->name("teacher.")->group(function () {
    Route::get("/dashboard", [TeacherController::class, "dashboard"])->name("dashboard");
    Route::get("/dashboard-metrics", [TeacherController::class, "dashboardMetrics"])->name("dashboard.metrics");
    Route::get("/students", [TeacherController::class, "students"])->name("students");
    Route::get("/students/{student}/report", [TeacherController::class, "studentReport"])->name("students.report");
    Route::post("/students/{student}/assign-class", [TeacherController::class, "assignClass"])->name("students.assign_class");
    Route::get("/classes", [TeacherController::class, "classes"])->name("classes");
    Route::get("/reports", [TeacherController::class, "reports"])->name("reports");
    Route::get("/worksheets", [TeacherController::class, "worksheets"])->name("worksheets");
});

// Student Routes
Route::middleware(["auth", "role:student"])->prefix("student")->name("student.")->group(function () {
    Route::get("/dashboard", [StudentController::class, "dashboard"])->name("dashboard");
    Route::get("/courses", [StudentController::class, "courses"])->name("courses");
    Route::get("/progress", [StudentController::class, "progress"])->name("progress");
    Route::get("/achievements", [StudentController::class, "achievements"])->name("achievements");
    Route::get("/class/{id}", [StudentController::class, "showClass"])->name("class");
    Route::get("/lesson/{id}", [StudentController::class, "showLesson"])->name("lesson");
    Route::post("/lesson/{id}/complete", [StudentController::class, "markComplete"])->name("lesson.complete");
    Route::get("/class/{id}/quiz", [StudentController::class, "showQuiz"])->name("quiz");
    Route::post("/class/{id}/quiz", [StudentController::class, "submitQuiz"])->name("quiz.submit");
});

// Parent Routes
Route::middleware(["auth", "role:parent"])->prefix("parent")->name("parent.")->group(function () {
    Route::get("/dashboard", [ParentController::class, "dashboard"])->name("dashboard");
});

require __DIR__."/auth.php";
