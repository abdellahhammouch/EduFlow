<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StripePaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use LogicException;
use Symfony\Component\HttpFoundation\Response;

class StripeWebhookController extends Controller
{
    public function __construct(
        private readonly StripePaymentService $stripePayments,
    ) {
    }

    public function handle(Request $request): JsonResponse
    {
        try {
            return response()->json(
                $this->stripePayments->handleWebhook(
                    $request->getContent(),
                    $request->header('Stripe-Signature'),
                ),
                Response::HTTP_OK,
            );
        } catch (LogicException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
