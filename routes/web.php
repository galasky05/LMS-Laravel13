<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Instructor\DashboardController as InstructorDashboardController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Redirect otomatis sesuai role setelah login
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Dashboard khusus per role, dilindungi middleware 'role'
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'role:instructor'])->prefix('instructor')->name('instructor.')->group(function () {
    Route::get('/dashboard', [InstructorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/courses', function () {
        return view('instructor.courses');
    })->name('courses');
    Route::get('/courses/{course}/lessons', function ($course) {
        return view('instructor.lessons', ['courseId' => $course]);
    })->name('lessons');
    Route::get('/courses/{course}/quizzes', function ($course) {
        return view('instructor.quizzes', ['courseId' => $course]);
    })->name('courses.quizzes');
    Route::get('/quizzes/{quiz}/questions', function ($quiz) {
        return view('instructor.questions', ['quizId' => $quiz]);
    })->name('quiz.questions');
    });

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/catalog', function () {
        return view('student.catalog');
    })->name('catalog');
    Route::get('/courses/{course}', function ($course) {
        return view('student.course-show', ['courseId' => $course]);
    })->name('course.show');
    Route::post('/payment/confirm/{enrollmentId}', [PaymentController::class, 'confirm'])->name('payment.confirm');
    Route::get('/quizzes/{quiz}', function ($quiz) {
        return view('student.quiz-take', ['quizId' => $quiz]);
    })->name('quiz.take');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';