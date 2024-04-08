<?php

namespace App\Services;

use App\DTO\MerchantDto;
use App\DTO\PaymentDto;
use App\DTO\UserDto;
use App\Entity\Payment;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Ramsey\Uuid\Uuid;

readonly class PaymentService
{
    public function __construct(
        private UserService $userService,
        private MerchantService $merchantService,
        private AccountService $accountService,
        private ObjectManager $objectManager,
    ) {}

    /**
     * @throws Exception
     */
    public function addPayment(PaymentDto $paymentDto): PaymentDto {
        $user = $this->userService->getUser($paymentDto->getUserId());
        $merchant = $this->merchantService->getMerchant($paymentDto->getMerchantId());
        $account = $this->accountService->getAccountForUser($paymentDto->getUserId());

        if ($account->getBalance() < $paymentDto->getAmount()) {
            throw new Exception("insufficient funds");
        }

        $this->accountService->decreaseBalance($account->getAccountId(), $paymentDto->getAmount());
        $payment = $this->toPayment($paymentDto, $user, $merchant);

        $this->objectManager->persist($payment);
        $this->objectManager->flush();

        return $this->paymentToPaymentDto($payment);
    }

    private function paymentToPaymentDto(Payment $payment): PaymentDto {
        return new PaymentDto(
            $payment->getPaymentId(),
            $payment->getUserId(),
            $payment->getMerchantId(),
            $payment->getAmount(),
        );
    }

    private function toPayment(
        PaymentDto $paymentDto,
        UserDto $userDto,
        MerchantDto $merchantDto
    ): Payment {
        $payment = new Payment();
        $payment->setPaymentId(Uuid::uuid4());
        $payment->setUserId($userDto->getUserId());
        $payment->setMerchantId($merchantDto->getMerchantId());
        $payment->setAmount($paymentDto->getAmount());

        return $payment;
    }
}
