<?php

namespace App\Services;

use App\DTO\AccountDto;
use App\Entity\Account;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Ramsey\Uuid\Uuid;

readonly class AccountService {
    public function __construct(
        private ObjectManager $objectManager,
    ) {

    }
    public function addAccountForUser(string $userId): AccountDto {
        $account = new Account();
        $account->setAccountId(Uuid::uuid4()->toString());
        $account->setUserId($userId);
        $account->setBalance(0.);

        $this->objectManager->persist($account);
        $this->objectManager->flush();

        return $this->accountToAccountDto($account);
    }

    public function getAccountForUser(string $userId): AccountDto {
        $account = $this->objectManager->getRepository(Account::class)->findOneBy(['userId' => $userId]);

        if (is_null($account)) {
            throw new Exception("Account not found for user " . $userId);
        }

        return $this->accountToAccountDto($account);
    }

    public function decreaseBalance(string $accountId, float $amount): AccountDto {
        $account = $this->getAccountById($accountId);

        $account->setBalance($account->getBalance() - $amount);
        $this->objectManager->flush();

        return $this->accountToAccountDto($account);
    }

    public function increaseBalance(string $accountId, float $amount): AccountDto {
        $account = $this->getAccountById($accountId);

        $account->setBalance($account->getBalance() + $amount);
        $this->objectManager->flush();

        return $this->accountToAccountDto($account);
    }

    private function accountToAccountDto(Account $account): AccountDto {
        return new AccountDto($account->getAccountId(), $account->getUserId(), $account->getBalance());
    }

    private function getAccountById(string $accountId): Account {
        $account = $this->objectManager->getRepository(Account::class)->findOneBy(['accountId' => $accountId]);

        if (is_null($account)) {
            throw new \Exception("Account with id " . $accountId . " not found");
        }

        return $account;
    }
}
