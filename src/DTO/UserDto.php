<?php

namespace App\DTO;

class UserDto
{
    private ?string $userId;
    private ?string $fullName;
    private ?string $email;

    public function __construct(
        ?string $userId = null,
        ?string $fullName = null,
        ?string $email = null
    ) {
        $this->userId = $userId;
        $this->fullName = $fullName;
        $this->email = $email;
    }

    public function toResponse(): array {
        return [
            'userId' => $this->userId,
            'fullName' => $this->fullName,
            'email' => $this->email,
        ];
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(?string $userId): void
    {
        $this->userId = $userId;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }
}
