<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wishlist\StoreWishlistRequest;
use App\Services\WishlistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WishlistController extends Controller
{
    public function __construct(
        private readonly WishlistService $wishlistService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json(
            $this->wishlistService->listByStudent((int) $request->user('api')->id),
        );
    }

    public function store(StoreWishlistRequest $request): JsonResponse
    {
        $wishlist = $this->wishlistService->add(
            (int) $request->user('api')->id,
            $request->integer('course_id'),
        );

        return response()->json($wishlist, Response::HTTP_CREATED);
    }

    public function destroy(Request $request, int $courseId): JsonResponse
    {
        $deleted = $this->wishlistService->remove((int) $request->user('api')->id, $courseId);

        return response()->json([
            'deleted' => $deleted,
        ]);
    }
}
