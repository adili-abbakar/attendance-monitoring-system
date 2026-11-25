<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SyncController;
use App\Http\Controllers\Api\AuthController;

Route::prefix('v1')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/sync/upsert-course', [SyncController::class, 'upsertCourse']);
        Route::post('/sync/upsert-lecture-session', [SyncController::class, 'upsertLectureSession']);
        Route::post('/sync/create-attendance-record', [SyncController::class, 'createAttendanceRecord']);
        Route::delete('/sync/delete-course', [SyncController::class, 'deleteCourse']);
        Route::delete('/sync/delete-lecture-session', [SyncController::class, 'deleteLectureSession']);
        Route::delete('/sync/delete-attendance-record', [SyncController::class, 'deleteAttendanceRecord']);
        Route::post('/sync/enrol-student', [SyncController::class, 'enrolStudent']);
        Route::delete('/sync/remove-enrolment', [SyncController::class, 'removeEnrolment']);
        Route::post('/sync/register-device', [SyncController::class, 'registerDevice']);
        Route::post('/sync/advance-checkpoint', [SyncController::class, 'advanceCheckpoint']);
        Route::get('/sync/change-feed', [SyncController::class, 'getChangeFeed']);
        Route::post('/sync/advance-change-feed-checkpoint', [SyncController::class, 'advanceChangeFeedCheckpoint']);
    });
});