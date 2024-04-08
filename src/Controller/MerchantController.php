<?php

namespace App\Controller;

use App\Services\MerchantService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class MerchantController
{
    public function __construct(
        private readonly MerchantService $merchantService,
    ){}

    #[Route('/api/merchants', methods: ['POST'])]
    public function addMerchant(Request $request): JsonResponse
    {
        $req = json_decode($request->getContent(), true);
        $res = $this->merchantService->addMerchant(
            $req['name'],
        );

        return new JsonResponse($res->toResponse());
    }
}
