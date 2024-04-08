<?php

namespace App\Controller;

use App\Services\UserService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController
{
    public function __construct(
        private readonly UserService $userService,
    ) {}

    /**
     * @throws Exception
     */
    public function addUser(Request $request): JsonResponse
    {
        $req = $request->request;

        $res = $this->userService->addUser(
            $req->getString('fullName'),
            $req->getString('email'),
            $req->getString('merchantId'),
        );

        return new JsonResponse($res->toResponse());
    }
}
