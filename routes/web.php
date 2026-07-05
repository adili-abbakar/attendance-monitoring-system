<?php

use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

Route::get('/',  [PublicController::class, 'home'])->name('home');

require __DIR__ . '/auth.php';
require __DIR__ . '/students.php';
