<?php

namespace App\DTO;

class PaymentDto
{
    private ?string $paymentId;
    private ?string $userId;
    private ?string $merchantId;
    private ?float $amount;

    public function __construct(
        ?string $paymentId = null,
        ?string $userId = null,
        ?string $merchantId = null,
        ?float $amount = null
    ) {
        $this->paymentId = $paymentId;
        $this->userId = $userId;
        $this->merchantId = $merchantId;
        $this->amount = $amount;
    }

    public function toResponse(): array {
        return [
            'payment_id' => $this->paymentId,
            'user_id' => $this->userId,
            'merchant_id' => $this->merchantId,
            'amount' => $this->amount,
        ];
    }

    public function getPaymentId(): ?string
    {
        return $this->paymentId;
    }

    public function setPaymentId(?string $paymentId): void
    {
        $this->paymentId = $paymentId;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(?string $userId): void
    {
        $this->userId = $userId;
    }

    public function getMerchantId(): ?string
    {
        return $this->merchantId;
    }

    public function setMerchantId(?string $merchantId): void
    {
        $this->merchantId = $merchantId;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): void
    {
        $this->amount = $amount;
    }
}
