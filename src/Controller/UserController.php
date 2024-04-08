<?php

namespace App\Controller;

use App\Services\UserService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UserController
{
    public function __construct(
        private readonly UserService $userService,
    ) {}

    /**
     * @throws Exception
     */
    #[Route('/api/users', methods: ['POST'])]
    public function addUser(Request $request): JsonResponse
    {
        $req = json_decode($request->getContent(), true);

        $res = $this->userService->addUser(
            $req['fullName'],
            $req['email'],
            $req['merchantId'],
        );

        return new JsonResponse($res->toResponse());
    }
}
