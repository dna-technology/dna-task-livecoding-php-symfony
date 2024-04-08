<?php

namespace App\Controller;

use App\Services\MerchantService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MerchantController
{
    public function __construct(
        private readonly MerchantService $merchantService,
    ){}

    public function addMerchant(Request $request): JsonResponse
    {
        $req = $request->request;

        $res = $this->merchantService->addMerchant(
            $req->getString('name'),
        );

        return new JsonResponse($res->toResponse());
    }
}
