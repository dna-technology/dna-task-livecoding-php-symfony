<?php

namespace App\Tests\Feature;

use App\Entity\Account;
use App\Entity\Merchant;
use App\Entity\User;
use App\Services\MerchantService;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private MerchantService $merchantService;

    protected function setUp(): void {
        self::createClient();

        /** @var Connection $connection */
        $connection = $this->getContainer()->get(Connection::class);
        $connection->beginTransaction();

        $this->merchantService = $this->getContainer()->get(MerchantService::class);

        parent::setUp();
    }

    public function testShouldSaveUser() {
        $merchantId = $this->thereIsMerchant();
        $fullName = 'John Doe';
        $email = 'john.doe@digitalnewagency.com';

        $payload = [
            'fullName' => $fullName,
            'email' => $email,
            'merchantId' => $merchantId,
        ];

        self::getClient()->jsonRequest('POST', 'api/users', $payload);

        self::assertResponseIsSuccessful();

        $response = json_decode(self::getClient()->getResponse()->getContent(), true);

        /** @var User $storedUser */
        $storedUser = self::getContainer()->get(ObjectManager::class)->getRepository(User::class)->findOneBy(['userId' => $response['userId']]);
        self::assertEquals($fullName, $storedUser->getFullName());
        self::assertEquals($merchantId, $storedUser->getMerchantId());
        self::assertEquals($email, $storedUser->getEmail());

        /** @var Account $storedAccount */
        $storedAccount = self::getContainer()->get(ObjectManager::class)->getRepository(Account::class)->findOneBy(['userId' => $response['userId']]);
        self::assertEquals(0.0, $storedAccount->getBalance());
        self::assertNotNull($storedAccount->getAccountId());
    }

    private function thereIsMerchant(): string {
        $merchant = $this->merchantService->addMerchant('DNA');
        return $merchant->getMerchantId();
    }
}
