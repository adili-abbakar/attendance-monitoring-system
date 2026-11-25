<?php

use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LecturerController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\AcademicSessionController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/',  [PublicController::class, 'home'])->name('home');

require __DIR__ . '/auth.php';
require __DIR__ . '/students.php';

// Admin Routes - Protected by admin middleware
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Lecturers
    Route::resource('lecturers', LecturerController::class)->except(['destroy']);
    Route::patch('lecturers/{lecturer}/toggle-status', [LecturerController::class, 'toggleStatus'])->name('lecturers.toggle-status');
    Route::delete('lecturers/{lecturer}', [LecturerController::class, 'destroy'])->name('lecturers.destroy');

    // Levels
    Route::resource('levels', LevelController::class)->except(['show']);
    Route::delete('levels/{level}', [LevelController::class, 'destroy'])->name('levels.destroy');

    // Academic Sessions
    Route::resource('academic-sessions', AcademicSessionController::class)->except(['show']);
    Route::delete('academic-sessions/{academicSession}', [AcademicSessionController::class, 'destroy'])->name('academic-sessions.destroy');

    // Courses
    Route::resource('courses', CourseController::class)->except(['show']);
    Route::delete('courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});