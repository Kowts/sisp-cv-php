<?php

declare(strict_types=1);

namespace Kowts\Sisp\Tests;

use Kowts\Sisp\Config\SispConfig;
use Kowts\Sisp\Domain\TransactionStatus;
use Kowts\Sisp\Domain\ValueObject\CallbackPayload;
use Kowts\Sisp\Infrastructure\Persistence\InMemoryTransactionStore;
use Kowts\Sisp\Infrastructure\Security\Fingerprint;
use Kowts\Sisp\Sisp;
use Kowts\Sisp\SispFactory;
use PHPUnit\Framework\TestCase;

final class CallbackHandlingTest extends TestCase
{
    public function testRejectsCallbackWithAmountDifferentFromOriginalPayment(): void
    {
        $store = new InMemoryTransactionStore();
        $sisp = $this->sisp($store);
        $sisp->createPayment($this->paymentData());

        $callback = $this->signedCallback([
            'merchantRef' => 'R1',
            'merchantSession' => 'S1',
            'amount' => '1501',
        ]);

        self::assertNull($sisp->handleCallback($callback));

        $transaction = $store->findByMerchantIdentifiers('R1', 'S1');
        self::assertNotNull($transaction);
        self::assertSame(TransactionStatus::PENDING, $transaction->status);
    }

    public function testDuplicateCallbackDoesNotRegressFinalTransactionStatus(): void
    {
        $store = new InMemoryTransactionStore();
        $sisp = $this->sisp($store);
        $sisp->createPayment($this->paymentData());

        $completed = $sisp->handleCallback($this->signedCallback([
            'merchantRef' => 'R1',
            'merchantSession' => 'S1',
            'amount' => '1500',
        ]));

        self::assertNotNull($completed);
        self::assertSame(TransactionStatus::COMPLETED, $completed->status);

        $failedRetry = $sisp->handleCallback($this->signedCallback([
            'merchantRef' => 'R1',
            'merchantSession' => 'S1',
            'amount' => '1500',
            'messageType' => '6',
            'merchantResponse' => '99',
        ]));

        self::assertNotNull($failedRetry);
        self::assertSame(TransactionStatus::COMPLETED, $failedRetry->status);
    }

    private function sisp(InMemoryTransactionStore $store): Sisp
    {
        return SispFactory::create(SispConfig::fromArray([
            'posId' => '90051',
            'posAutCode' => 'secret',
            'url' => 'https://gateway.example/pay',
            'urlMerchantResponse' => 'https://app.example/callback',
            'transactionStore' => $store,
        ]));
    }

    /**
     * @return array<string,string>
     */
    private function paymentData(): array
    {
        return [
            'amount' => '1500',
            'merchantRef' => 'R1',
            'merchantSession' => 'S1',
            'timeStamp' => '2026-07-14 10:00:00',
        ];
    }

    /**
     * @param array<string,string> $overrides
     */
    private function signedCallback(array $overrides): CallbackPayload
    {
        $payload = new CallbackPayload(array_merge([
            'merchantRef' => '',
            'merchantSession' => '',
            'timeStamp' => '2026-07-14 10:00:05',
            'amount' => '0',
            'currency' => '132',
            'transactionCode' => '1',
            'transactionID' => 'T1',
            'messageType' => '8',
            'merchantResponse' => '00',
            'responseCode' => '00',
            'posID' => '90051',
            'messageID' => 'M1',
            'currencyProvided' => true,
            'transactionCodeProvided' => true,
            'posIDProvided' => true,
        ], $overrides));

        $payload->fingerprint = Fingerprint::callback(Fingerprint::computeToken('secret'), $payload);

        return $payload;
    }
}
