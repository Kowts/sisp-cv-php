<?php

declare(strict_types=1);

namespace Kowts\Sisp\Tests;

use Kowts\Sisp\Config\SispConfig;
use Kowts\Sisp\Infrastructure\Persistence\PdoTransactionStore;
use Kowts\Sisp\SispFactory;
use PDO;
use PHPUnit\Framework\TestCase;

final class PdoStorageTest extends TestCase
{
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
}
