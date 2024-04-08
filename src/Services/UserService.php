<?php

namespace App\Services;

use App\DTO\UserDto;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Ramsey\Uuid\Uuid;

readonly class UserService
{
    public function __construct(
        private AccountService $accountService,
        private ObjectManager $objectManager,
    ) {}

    public function addUser(string $fullName, string $email, string $merchantId): UserDto {
        $user = new User();
        $user->setUserId(Uuid::uuid4()->toString());
        $user->setFullName($fullName);
        $user->setEmail($email);
        $user->setMerchantId($merchantId);

        $this->objectManager->persist($user);
        $this->objectManager->flush();

        $this->accountService->addAccountForUser($user->getUserId());
        return $this->userToUserDto($user);
    }

    public function getUser(string $userId): UserDto {
        $user = $this->objectManager->getRepository(User::class)->findOneBy(['userId' => $userId]);
        if (is_null($user)) {
            throw new Exception("User with id " . $userId . " not found");
        }

        return $this->userToUserDto($user);
    }

    private function userToUserDto(User $user): UserDto {
        return new UserDto($user->getUserId(), $user->getFullName(), $user->getEmail());
    }
}
