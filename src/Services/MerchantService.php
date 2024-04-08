<?php

namespace App\Services;

use App\DTO\MerchantDto;
use App\Entity\Merchant;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Ramsey\Uuid\Uuid;

readonly class MerchantService
{
    public function __construct(private ObjectManager $objectManager)
    {
    }

    public function addMerchant(string $name): MerchantDto {
        $merchant = new Merchant();
        $merchant->setMerchantId(Uuid::uuid4());
        $merchant->setName($name);

        $this->objectManager->persist($merchant);
        $this->objectManager->flush();

        return $this->merchantToMerchantDto($merchant);
    }

    public function getMerchant(string $merchantId): MerchantDto {
        $repository = $this->objectManager->getRepository(Merchant::class);
        $merchant = $repository->findOneBy(['merchantId' => $merchantId]);

        if (is_null($merchant)) {
            throw new Exception("Merchant with id " . $merchantId . " not found");
        }

        return $this->merchantToMerchantDto($merchant);
    }

    public function merchantToMerchantDto(Merchant $merchant): MerchantDto {
        return new MerchantDto($merchant->getMerchantId(), $merchant->getName());
    }
}
