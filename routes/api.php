<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\CourseGroupController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\RecommendationController;
use App\Http\Controllers\Api\StripeWebhookController;
use App\Http\Controllers\Api\TeacherStatsController;
use App\Http\Controllers\Api\WishlistController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);

    Route::get('/health', fn () => response()->json([
        'message' => 'EduFlow API is ready.',
    ]));

    Route::prefix('auth')->group(function (): void {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('/reset-password', [AuthController::class, 'resetPassword']);

        Route::middleware('auth:api')->group(function (): void {
            Route::get('/me', [AuthController::class, 'me']);
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::post('/refresh', [AuthController::class, 'refresh']);
        });
    });

    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{course}', [CourseController::class, 'show']);

    Route::middleware(['auth:api', 'role:teacher'])->prefix('teacher')->group(function (): void {
        Route::get('/courses', [CourseController::class, 'myCourses']);
        Route::post('/courses', [CourseController::class, 'store']);
        Route::put('/courses/{course}', [CourseController::class, 'update']);
        Route::delete('/courses/{course}', [CourseController::class, 'destroy']);
        Route::get('/courses/stats', [TeacherStatsController::class, 'courseStats']);
        Route::get('/courses/{courseId}/enrollments', [EnrollmentController::class, 'index']);
        Route::get('/courses/{courseId}/groups', [CourseGroupController::class, 'index']);
        Route::get('/courses/{courseId}/groups/{groupId}/participants', [CourseGroupController::class, 'participants']);
    });

    Route::middleware(['auth:api', 'role:student'])->prefix('student')->group(function (): void {
        Route::get('/recommendations', [RecommendationController::class, 'index']);
        Route::get('/wishlist', [WishlistController::class, 'index']);
        Route::post('/wishlist', [WishlistController::class, 'store']);
        Route::delete('/wishlist/{courseId}', [WishlistController::class, 'destroy']);
        Route::post('/payments/intent', [PaymentController::class, 'createIntent']);
        Route::get('/enrollments', [EnrollmentController::class, 'myEnrollments']);
        Route::post('/enrollments', [EnrollmentController::class, 'store']);
        Route::post('/courses/{courseId}/withdraw', [EnrollmentController::class, 'withdraw']);
    });
});
