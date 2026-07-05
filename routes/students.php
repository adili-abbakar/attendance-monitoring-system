<?php

use App\Http\Controllers\User\StudentController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {
    Route::get('students', [StudentController::class, 'index'])->name('students.index');
});
