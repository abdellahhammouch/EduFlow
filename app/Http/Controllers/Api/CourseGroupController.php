<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\CourseGroupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CourseGroupController extends Controller
{
    public function __construct(
        private readonly CourseGroupService $courseGroupService,
    ) {
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
                'message' => 'You can only view groups for your own courses.',
            ], Response::HTTP_FORBIDDEN);
        }

        return response()->json($this->courseGroupService->listByCourse($courseId));
    }

    public function participants(Request $request, int $courseId, int $groupId): JsonResponse
    {
        $course = Course::query()->find($courseId);

        if ($course === null) {
            return response()->json([
                'message' => 'Course not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        if ((int) $course->teacher_id !== (int) $request->user('api')->id) {
            return response()->json([
                'message' => 'You can only view groups for your own courses.',
            ], Response::HTTP_FORBIDDEN);
        }

        $group = $this->courseGroupService->showParticipants($courseId, $groupId);

        if ($group === null) {
            return response()->json([
                'message' => 'Group not found for this course.',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json($group);
    }
}
