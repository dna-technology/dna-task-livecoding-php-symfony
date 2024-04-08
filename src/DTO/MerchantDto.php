<?php

namespace App\DTO;

class MerchantDto
{
    private ?string $merchantId;

    private ?string $name;

    public function __construct(
        ?string $merchantId = null,
        ?string $name = null
    ) {
        $this->merchantId = $merchantId;
        $this->name = $name;
    }

    public function toResponse(): array {
        return [
            'merchant_id' => $this->merchantId,
            'name' => $this->name,
        ];
    }

    public function getMerchantId(): ?string
    {
        return $this->merchantId;
    }

    public function setMerchantId(?string $merchantId): void
    {
        $this->merchantId = $merchantId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }


}
