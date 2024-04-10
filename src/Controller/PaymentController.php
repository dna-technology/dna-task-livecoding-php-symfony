<?php

namespace App\Controller;

use App\DTO\PaymentDto;
use App\Services\PaymentService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class PaymentController
{
    public function __construct(
        private readonly PaymentService $paymentService,
    ) {}

    /**
     * @throws Exception
     */
    #[Route('/api/transactions', methods: ['POST'])]
    public function addPayment(Request $request): JsonResponse
    {
        $req = json_decode($request->getContent(), true);

        $paymentDto = new PaymentDto(
            null,
            $req['user_id'],
            $req['merchant_id'],
            floatval($req['amount']),
        );

        $res = $this->paymentService->addPayment($paymentDto);

        return new JsonResponse($res->toResponse());
    }
}
