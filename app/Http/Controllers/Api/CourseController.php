<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CourseController extends Controller
{
    public function __construct(
        private readonly CourseService $courseService,
    ) {
    }

    public function myCourses(Request $request): JsonResponse
    {
        $teacherId = (int) $request->user('api')->id;

        return response()->json($this->courseService->listByTeacher($teacherId));
    }

    public function index(): JsonResponse
    {
        return response()->json($this->courseService->listAvailable());
    }

    public function show(int $course): JsonResponse
    {
        $courseDetails = $this->courseService->showAvailable($course);

        if ($courseDetails === null) {
            return response()->json([
                'message' => 'Course not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json($courseDetails);
    }

    public function store(StoreCourseRequest $request): JsonResponse
    {
        $course = $this->courseService->create([
            ...$request->validated(),
            'teacher_id' => $request->user('api')->id,
        ]);

        return response()->json($course, Response::HTTP_CREATED);
    }

    public function update(UpdateCourseRequest $request, Course $course): JsonResponse
    {
        if ((int) $course->teacher_id !== (int) $request->user('api')->id) {
            return response()->json([
                'message' => 'You can only update your own courses.',
            ], Response::HTTP_FORBIDDEN);
        }

        $this->courseService->update($course, $request->validated());

        return response()->json($course->fresh(['teacher', 'domain']));
    }

    public function destroy(Request $request, Course $course): JsonResponse
    {
        if ((int) $course->teacher_id !== (int) $request->user('api')->id) {
            return response()->json([
                'message' => 'You can only delete your own courses.',
            ], Response::HTTP_FORBIDDEN);
        }

        $this->courseService->delete($course);

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
