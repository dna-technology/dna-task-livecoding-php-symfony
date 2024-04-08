<?php

namespace App\DTO;

class AccountDto
{
    private ?string $accountId;

    private ?string $userId;

    private ?float $balance;

    public function __construct(
        ?string $accountId = null,
        ?string $userId = null,
        ?float  $balance = null
    ) {
        $this->accountId = $accountId;
        $this->userId = $userId;
        $this->balance = $balance;
    }

    public function toResponse(): array {
        return [
            'account_id' => $this->accountId,
            'user_id' => $this->userId,
            'balance' => $this->balance,
        ];
    }

    public function getAccountId(): string
    {
        return $this->accountId;
    }

    public function setAccountId(string $accountId): void
    {
        $this->accountId = $accountId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): void
    {
        $this->balance = $balance;
    }
}
