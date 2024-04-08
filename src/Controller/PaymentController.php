<?php

namespace App\Controller;

use App\DTO\PaymentDto;
use App\Services\PaymentService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PaymentController
{
    public function __construct(
        private readonly PaymentService $paymentService,
    ) {}

    /**
     * @throws Exception
     */
    public function addPayment(Request $request): JsonResponse
    {
        $req = $request->request;

        $paymentDto = new PaymentDto(
            null,
            $req->getString('user_id'),
            $req->getString('merchant_id'),
            floatval($req->getString('amount')),
        );

        $res = $this->paymentService->addPayment($paymentDto);

        return new JsonResponse($res->toResponse());
    }
}
