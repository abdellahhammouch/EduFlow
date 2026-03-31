<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RecommendationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function __construct(
        private readonly RecommendationService $recommendationService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json(
            $this->recommendationService->forStudent((int) $request->user('api')->id),
        );
    }
}
