<?php

namespace App\Tests\Feature;

use App\Entity\Account;
use App\Entity\Payment;
use App\Services\AccountService;
use App\Services\MerchantService;
use App\Services\UserService;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Throwable;
use Doctrine\DBAL\Connection;

final class PaymentControllerTest extends WebTestCase
{
    private UserService $userService;
    private MerchantService $merchantService;
    private AccountService $accountService;

    protected function setUp(): void {
        self::createClient();

        /** @var Connection $connection */
        $connection = $this->getContainer()->get(Connection::class);
        $connection->beginTransaction();

        $this->userService = $this->getContainer()->get(UserService::class);
        $this->merchantService = $this->getContainer()->get(MerchantService::class);
        $this->accountService = $this->getContainer()->get(AccountService::class);

        parent::setUp();
    }

    private function thereIsMerchant(): string {
        $merchant = $this->merchantService->addMerchant('DNA');
        return $merchant->getMerchantId();
    }

    /**
     * @throws Exception
     */
    private function thereIsUser(string $merchantId): string {
        $user = $this->userService->addUser(
            'Jan Kowalski',
            'jan.kowalski@digitalnewagency.com',
            $merchantId,
        );

        return $user->getUserId();
    }

    /**
     * @throws Exception
     */
    private function userHasPositiveAccountBalance(string $userId): float {
        $accountId = $this->accountService->getAccountForUser($userId)->getAccountId();
        return $this->accountService->increaseBalance($accountId, 100.0)->getBalance();
    }

    /**
     * @throws Throwable
     */
    public function test_should_save_payment_transaction(): void
    {
        // given
        $merchantId = $this->thereIsMerchant();
        $userId = $this->thereIsUser($merchantId);
        $initialBalance = $this->userHasPositiveAccountBalance($userId);

        // when
        $amount = 10.0;
        $payload = [
            'user_id' => $userId,
            'merchant_id' => $merchantId,
            'amount' => $amount,
        ];

        $client = self::getClient();
        $client->jsonRequest('POST', 'api/transactions', $payload);
        self::assertResponseIsSuccessful();
        $response = json_decode(self::getClient()->getResponse()->getContent(), true);
        // then
        self::assertEquals($userId, $response['user_id']);
        self::assertEquals($merchantId, $response['merchant_id']);
        self::assertEquals($amount, $response['amount']);

        /** @var Payment $storedPayment */
        $storedPayment = $this->getContainer()->get(ObjectManager::class)->getRepository(Payment::class)->findOneBy(['paymentId' => $response['payment_id']]);
        self::assertEquals($userId, $storedPayment->getUserId());
        self::assertEquals($merchantId, $storedPayment->getMerchantId());
        self::assertEquals($amount, $storedPayment->getAmount());

        /** @var Account $account */
        $account = $this->getContainer()->get(ObjectManager::class)->getRepository(Account::class)->findOneBy(['userId' => $userId]);
        self::assertEquals($initialBalance - $amount, $account->getBalance());
    }
}
