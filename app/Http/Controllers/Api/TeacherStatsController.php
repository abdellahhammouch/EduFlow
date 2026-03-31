<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TeacherStatsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeacherStatsController extends Controller
{
    public function __construct(
        private readonly TeacherStatsService $teacherStatsService,
    ) {
    }

    public function courseStats(Request $request): JsonResponse
    {
        return response()->json(
            $this->teacherStatsService->courseStats((int) $request->user('api')->id),
        );
    }
}
