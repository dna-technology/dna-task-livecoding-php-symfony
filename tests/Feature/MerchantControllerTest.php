<?php

namespace App\Tests\Feature;

use App\Entity\Merchant;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class MerchantControllerTest extends WebTestCase {
    protected function setUp(): void {
        self::createClient();

        /** @var Connection $connection */
        $connection = $this->getContainer()->get(Connection::class);
        $connection->beginTransaction();

        parent::setUp();
    }

    protected function tearDown(): void
    {
        /** @var Connection $connection */
        $connection = $this->getContainer()->get(Connection::class);
        $connection->rollBack();

        parent::tearDown();
    }

    public function testShouldCreateMerchant() {
        $merchantName = 'DNA';
        $payload = ['name' => $merchantName];
        $client = self::getClient();
        $client->jsonRequest('POST', 'api/merchants', $payload);

        self::assertResponseIsSuccessful();

        $response = json_decode(self::getClient()->getResponse()->getContent(), true);

        /** @var Merchant $storedMerchant */
        $storedMerchant = self::getContainer()->get(ObjectManager::class)->getRepository(Merchant::class)->findOneBy(['merchantId' => $response['merchant_id']]);
        self::assertEquals($merchantName, $storedMerchant->getName());
    }
}
