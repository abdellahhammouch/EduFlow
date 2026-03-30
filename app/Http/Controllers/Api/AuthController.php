<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use LogicException;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,
    ) {
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        return response()->json(
            $this->authService->register($request->validated()),
            201,
        );
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            return response()->json($this->authService->login($request->validated()));
        } catch (ValidationException $exception) {
            return response()->json([
                'message' => 'Invalid credentials.',
                'errors' => $exception->errors(),
            ], 401);
        }
    }

    public function me(): JsonResponse
    {
        return response()->json($this->authService->me());
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->json([
            'message' => 'Successfully logged out.',
        ]);
    }

    public function refresh(): JsonResponse
    {
        return response()->json($this->authService->refresh());
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        try {
            $this->authService->sendPasswordResetLink($request->string('email')->value());

            return response()->json([
                'message' => 'Password reset link sent successfully.',
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'message' => 'Unable to send reset link.',
                'errors' => $exception->errors(),
            ], 422);
        }
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $this->authService->resetPassword($request->validated());

            return response()->json([
                'message' => 'Password reset successfully.',
            ]);
        } catch (LogicException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }
    }
}
