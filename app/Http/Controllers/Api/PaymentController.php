<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\CreatePaymentIntentRequest;
use App\Services\StripePaymentService;
use Illuminate\Http\JsonResponse;
use LogicException;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    public function __construct(
        private readonly StripePaymentService $stripePayments,
    ) {
    }

    public function createIntent(CreatePaymentIntentRequest $request): JsonResponse
    {
        try {
            return response()->json(
                $this->stripePayments->createPaymentIntent(
                    (int) $request->user('api')->id,
                    $request->integer('course_id'),
                ),
                Response::HTTP_CREATED,
            );
        } catch (LogicException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
