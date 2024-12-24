<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\LearningController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentAnswerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::resource('course', CourseController::class)->middleware('role:teacher');
        Route::get('/course/question/create/{course}', [CourseController::class, 'create'])->middleware('role:teacher')->name('courses.question');
        Route::post('/course/question/create/{course}', [CourseController::class, 'store'])->middleware('role:teacher')->name('courses.question.store');
        Route::resource('course_questions', CourseController::class)->middleware('role:teacher');
        Route::get('course/students/show/{course}', [CourseController::class, 'index'])->middleware('role:teacher')->name('courses.course_students.index');
        Route::get('course/students/create/{course}', [CourseController::class, 'create'])->middleware('role:teacher')->name('courses.course_students.create');
        Route::post('course/students/create/save/{course}', [CourseController::class, 'store'])->middleware('role:teacher')->name('courses.course_students.store');
        Route::get('learning/finished/{course}', [LearningController::class, 'learning_finished'])->middleware('role:student')->name('learning.finished.course');
        Route::get('learning/rapport/{course}', [LearningController::class, 'learning_rapport'])->middleware('role:student')->name('learning.rapport.course');
        //kelas yang diberikan oleh guru
        Route::get('/learning', [LearningController::class, 'index'])->middleware('role:student')->name('learning.index');
        Route::get('/learning/{course}/{question}', [LearningController::class, 'learning'])->middleware('role:student')->name('learning.course');
        Route::post('/learning/{course}/{question}', [StudentAnswerController::class, 'store'])->middleware('role:student')->name('learning.course.answer.store');

    });
});

require __DIR__ . '/auth.php';
