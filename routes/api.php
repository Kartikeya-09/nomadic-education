<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\AssessmentController;
use App\Http\Controllers\Api\ClassController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\FileUploadController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\ParentController;
use App\Http\Controllers\Api\PdfController;
use App\Http\Controllers\Api\SchoolController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TeacherController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

Route::middleware('auth:api')->group(function () {
    Route::apiResource('students', StudentController::class)->middleware('role:admin|teacher');
    Route::apiResource('teachers', TeacherController::class)->middleware('role:admin');
    Route::apiResource('parents', ParentController::class)->middleware('role:admin|teacher');
    Route::apiResource('schools', SchoolController::class)->middleware('role:admin');
    Route::apiResource('classes', ClassController::class)->middleware('role:admin|teacher');
    Route::apiResource('attendance', AttendanceController::class)->middleware('role:admin|teacher');
    Route::apiResource('lessons', LessonController::class)->middleware('role:admin|teacher');
    Route::apiResource('assessments', AssessmentController::class)->middleware('role:admin|teacher');
    Route::apiResource('content', ContentController::class)->middleware('role:admin|teacher');
    Route::post('uploads', [FileUploadController::class, 'store'])->middleware('role:admin|teacher');
    Route::post('pdf/worksheet', [PdfController::class, 'worksheet'])->middleware('role:admin|teacher');
});
