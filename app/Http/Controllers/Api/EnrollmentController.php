<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Enrollment\StoreEnrollmentRequest;
use App\Models\Course;
use App\Services\EnrollmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use LogicException;
use Symfony\Component\HttpFoundation\Response;

class EnrollmentController extends Controller
{
    public function __construct(
        private readonly EnrollmentService $enrollmentService,
    ) {
    }

    public function store(StoreEnrollmentRequest $request): JsonResponse
    {
        try {
            $enrollment = $this->enrollmentService->create([
                ...$request->validated(),
                'student_id' => $request->user('api')->id,
            ]);

            return response()->json($enrollment, Response::HTTP_CREATED);
        } catch (LogicException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function index(Request $request, int $courseId): JsonResponse
    {
        $course = Course::query()->find($courseId);

        if ($course === null) {
            return response()->json([
                'message' => 'Course not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        if ((int) $course->teacher_id !== (int) $request->user('api')->id) {
            return response()->json([
                'message' => 'You can only view enrollments for your own courses.',
            ], Response::HTTP_FORBIDDEN);
        }

        return response()->json($this->enrollmentService->listByCourse($courseId));
    }

    public function myEnrollments(Request $request): JsonResponse
    {
        return response()->json(
            $this->enrollmentService->listByStudent((int) $request->user('api')->id),
        );
    }

    public function withdraw(Request $request, int $courseId): JsonResponse
    {
        try {
            $enrollment = $this->enrollmentService->withdraw(
                (int) $request->user('api')->id,
                $courseId,
            );

            return response()->json($enrollment);
        } catch (LogicException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
