<?php

declare(strict_types=1);

namespace Kowts\Sisp\Tests;

use Kowts\Sisp\Config\SispConfig;
use Kowts\Sisp\Domain\TransactionStatus;
use Kowts\Sisp\Domain\ValueObject\CallbackPayload;
use Kowts\Sisp\Infrastructure\Persistence\SispSchema;
use Kowts\Sisp\Infrastructure\Persistence\PdoTransactionStore;
use Kowts\Sisp\SispFactory;
use PDO;
use PHPUnit\Framework\TestCase;

final class PdoStorageTest extends TestCase
{
    public function testSqlServerSchemaUsesCompatibleStatements(): void
    {
        $statements = implode("\n", SispSchema::statements('sqlsrv'));

        self::assertStringContainsString('BIGINT IDENTITY(1,1) PRIMARY KEY', $statements);
        self::assertStringContainsString('VARCHAR(MAX)', $statements);
        self::assertStringContainsString("OBJECT_ID(N'dbo.sisp_transactions', N'U')", $statements);
        self::assertStringNotContainsString('CREATE TABLE IF NOT EXISTS', $statements);
    }

    public function testCreatePaymentPersistsTransactionAndAttempt(): void
    {
        $pdo = new PDO('sqlite::memory:');
        $sisp = SispFactory::create(SispConfig::fromArray([
            'posId' => '90051',
            'posAutCode' => 'secret',
            'url' => 'https://gateway.example/pay',
            'urlMerchantResponse' => 'https://app.example/callback',
            'pdo' => $pdo,
        ]));

        $request = $sisp->createPayment([
            'amount' => '1500',
            'merchantRef' => 'R1',
            'merchantSession' => 'S1',
            'timeStamp' => '2026-06-12 10:00:00',
        ]);

        $store = new PdoTransactionStore($pdo, false);
        $transaction = $store->findByMerchantIdentifiers($request->merchantRef, $request->merchantSession);

        self::assertNotNull($transaction);
        self::assertSame('pending', $transaction->status);
        self::assertSame(1, (int) $pdo->query('SELECT COUNT(*) FROM sisp_transaction_attempts')->fetchColumn());
    }

    public function testPersistenceRedactsSensitiveFieldsAndEnablesPdoExceptions(): void
    {
        $pdo = new PDO('sqlite::memory:', null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT]);
        $sisp = SispFactory::create(SispConfig::fromArray([
            'posId' => '90051',
            'posAutCode' => 'secret',
            'url' => 'https://gateway.example/pay',
            'urlMerchantResponse' => 'https://app.example/callback',
            'pdo' => $pdo,
        ]));

        self::assertSame(PDO::ERRMODE_EXCEPTION, $pdo->getAttribute(PDO::ATTR_ERRMODE));

        $request = $sisp->createPayment([
            'amount' => '1500',
            'merchantRef' => 'R2',
            'merchantSession' => 'S2',
            'timeStamp' => '2026-06-12 10:00:00',
            'token' => 'sensitive-token',
        ]);

        $store = new PdoTransactionStore($pdo, false);
        $transaction = $store->findByMerchantIdentifiers($request->merchantRef, $request->merchantSession);
        self::assertNotNull($transaction);
        self::assertArrayNotHasKey('token', $transaction->payload);
        self::assertArrayNotHasKey('fingerprint', $transaction->payload);

        $completed = $store->applyCallback(
            $transaction,
            $this->callbackPayload('R2', 'S2', 'T1'),
            TransactionStatus::COMPLETED
        );

        /** @var array<string,mixed> $callback */
        $callback = $completed->payload['callback'];
        self::assertArrayNotHasKey('resultFingerPrint', $callback);
        self::assertArrayNotHasKey('merchantRespPan', $callback);
        self::assertArrayNotHasKey('merchantRespClientReceipt', $callback);
        self::assertArrayNotHasKey('merchantRespAdditionalErrorMessage', $callback);
    }

    public function testStaleCallbackCannotOverwriteFinalTransaction(): void
    {
        $pdo = new PDO('sqlite::memory:');
        $sisp = SispFactory::create(SispConfig::fromArray([
            'posId' => '90051',
            'posAutCode' => 'secret',
            'url' => 'https://gateway.example/pay',
            'urlMerchantResponse' => 'https://app.example/callback',
            'pdo' => $pdo,
        ]));
        $request = $sisp->createPayment([
            'amount' => '1500',
            'merchantRef' => 'R3',
            'merchantSession' => 'S3',
            'timeStamp' => '2026-06-12 10:00:00',
        ]);

        $store = new PdoTransactionStore($pdo, false);
        $pending = $store->findByMerchantIdentifiers($request->merchantRef, $request->merchantSession);
        self::assertNotNull($pending);

        $store->applyCallback($pending, $this->callbackPayload('R3', 'S3', 'T1'), TransactionStatus::COMPLETED);
        $result = $store->applyCallback($pending, $this->callbackPayload('R3', 'S3', 'T2'), TransactionStatus::FAILED);

        self::assertSame(TransactionStatus::COMPLETED, $result->status);
        self::assertSame('T1', $result->gatewayTransactionId);
    }

    private function callbackPayload(
        string $merchantRef,
        string $merchantSession,
        string $transactionId
    ): CallbackPayload {
        return new CallbackPayload([
            'merchantRef' => $merchantRef,
            'merchantSession' => $merchantSession,
            'timeStamp' => '2026-06-12 10:00:05',
            'amount' => '1500',
            'currency' => '132',
            'transactionCode' => '1',
            'transactionID' => $transactionId,
            'messageType' => '8',
            'merchantResponse' => '00',
            'responseCode' => '00',
            'fingerprint' => 'sensitive-fingerprint',
            'pan' => '4111111111111111',
            'clientReceipt' => 'sensitive-receipt',
            'additionalErrorMessage' => 'sensitive-error',
        ]);
    }
}
