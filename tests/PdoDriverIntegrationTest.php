<?php

declare(strict_types=1);

namespace Kowts\Sisp\Tests;

use Kowts\Sisp\Config\SispConfig;
use Kowts\Sisp\Infrastructure\Persistence\PdoPaymentIntentStore;
use Kowts\Sisp\Infrastructure\Persistence\PdoTransactionStore;
use Kowts\Sisp\SispFactory;
use PDO;
use PHPUnit\Framework\TestCase;

final class PdoDriverIntegrationTest extends TestCase
{
    public function testMySql(): void
    {
        $this->exerciseDriver('MYSQL');
    }

    public function testPostgreSql(): void
    {
        $this->exerciseDriver('PGSQL');
    }

    public function testSqlServer(): void
    {
        $this->exerciseDriver('SQLSRV');
    }

    private function exerciseDriver(string $driver): void
    {
        $dsn = getenv("SISP_TEST_{$driver}_DSN");

        if ($dsn === false || $dsn === '') {
            self::markTestSkipped("A ligação de teste {$driver} não está configurada.");
        }

        $user = getenv("SISP_TEST_{$driver}_USER") ?: null;
        $password = getenv("SISP_TEST_{$driver}_PASSWORD") ?: null;
        $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $suffix = strtoupper(bin2hex(random_bytes(5)));

        try {
            $this->dropSchema($pdo);
            $sisp = SispFactory::create(SispConfig::fromArray([
                'posId' => '90051',
                'posAutCode' => 'secret',
                'url' => 'https://gateway.example/pay',
                'urlMerchantResponse' => 'https://app.example/callback',
                'pdo' => $pdo,
            ]));
            $request = $sisp->createPayment([
                'amount' => '1500',
                'merchantRef' => 'REF' . $suffix,
                'merchantSession' => 'SES' . $suffix,
                'timeStamp' => '2026-07-14 10:00:00',
            ]);

            $transactions = new PdoTransactionStore($pdo, false);
            $transaction = $transactions->findByMerchantIdentifiers(
                $request->merchantRef,
                $request->merchantSession
            );
            $intents = new PdoPaymentIntentStore($pdo, false);
            $intents->reserve('intent-' . strtolower($suffix));

            self::assertNotNull($transaction);
            if ($transaction === null) {
                throw new \RuntimeException('A transação SISP não foi persistida.');
            }

            $intents->link('intent-' . strtolower($suffix), $transaction->id);
            $intent = $intents->find('intent-' . strtolower($suffix));

            self::assertSame('pending', $transaction->status);
            self::assertSame(
                1,
                (int) $pdo->query('SELECT COUNT(*) FROM sisp_transaction_attempts')->fetchColumn()
            );
            self::assertNotNull($intent);
            if ($intent === null) {
                throw new \RuntimeException('A intenção de pagamento não foi persistida.');
            }

            self::assertSame($transaction->id, (int) $intent['transaction_id']);
        } finally {
            $this->dropSchema($pdo);
        }
    }

    private function dropSchema(PDO $pdo): void
    {
        $tables = [
            'sisp_rate_limits',
            'sisp_blacklist',
            'sisp_request_metadata',
            'sisp_transaction_logs',
            'sisp_payment_intents',
            'sisp_transaction_attempts',
            'sisp_transactions',
        ];

        foreach ($tables as $table) {
            $pdo->exec('DROP TABLE IF EXISTS ' . $table);
        }
    }
}
