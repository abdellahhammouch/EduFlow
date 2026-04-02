<?php

use App\Models\Domain;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::view('/courses', 'courses.index')->name('courses.index');

Route::get('/courses/{course}', function (int $course) {
    return view('courses.show', [
        'courseId' => $course,
    ]);
})->name('courses.show');

Route::view('/login', 'auth.login')->name('login');

Route::get('/register', function () {
    return view('auth.register', [
        'domains' => Domain::query()->orderBy('name')->get(),
    ]);
})->name('register');

Route::view('/forgot-password', 'auth.forgot-password')->name('password.forgot');

Route::get('/reset-password', function () {
    return view('auth.reset-password', [
        'token' => request('token'),
        'email' => request('email'),
    ]);
})->name('password.reset');

Route::view('/teacher/dashboard', 'dashboard.teacher')->name('dashboard.teacher');
Route::view('/student/dashboard', 'dashboard.student')->name('dashboard.student');
Route::view('/student/recommendations', 'student.recommendations')->name('student.recommendations');
Route::view('/student/wishlist', 'student.wishlist')->name('student.wishlist');
Route::view('/student/enrollments', 'student.enrollments')->name('student.enrollments');

Route::get('/teacher/courses', function () {
    return view('teacher.courses', [
        'domains' => Domain::query()->orderBy('name')->get(),
    ]);
})->name('teacher.courses');

Route::view('/teacher/groups', 'teacher.groups')->name('teacher.groups');
Route::view('/teacher/stats', 'teacher.stats')->name('teacher.stats');
