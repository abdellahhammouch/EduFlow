<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wishlist\StoreWishlistRequest;
use App\Services\WishlistService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class WishlistController extends Controller
{
    public function __construct(
        private readonly WishlistService $wishlistService,
    ) {
    }

    public function index(int $studentId): JsonResponse
    {
        return response()->json($this->wishlistService->listByStudent($studentId));
    }

    public function store(StoreWishlistRequest $request): JsonResponse
    {
        $wishlist = $this->wishlistService->add(
            $request->integer('student_id'),
            $request->integer('course_id'),
        );

        return response()->json($wishlist, Response::HTTP_CREATED);
    }

    public function destroy(int $studentId, int $courseId): JsonResponse
    {
        $deleted = $this->wishlistService->remove($studentId, $courseId);

        return response()->json([
            'deleted' => $deleted,
        ]);
    }
}
