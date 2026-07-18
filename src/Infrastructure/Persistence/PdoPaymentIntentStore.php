<?php

declare(strict_types=1);

namespace Kowts\Sisp\Infrastructure\Persistence;

use Kowts\Sisp\Contract\PaymentIntentStore;
use PDO;

final class PdoPaymentIntentStore implements PaymentIntentStore
{
    private PDO $pdo;

    public function __construct(PDO $pdo, bool $autoMigrate = true)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($autoMigrate) {
            SispSchema::migrate($this->pdo);
        }
    }

    public function reserve(string $key, string $status = 'reserved'): void
    {
        $now = gmdate('c');
        $statement = $this->pdo->prepare(
            'INSERT INTO sisp_payment_intents (intent_key, status, created_at, updated_at)
             VALUES (:intent_key, :status, :created_at, :updated_at)'
        );
        $statement->execute([
            'intent_key' => $key,
            'status' => $status,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    public function link(string $key, int $transactionId): void
    {
        $statement = $this->pdo->prepare(
            'UPDATE sisp_payment_intents SET transaction_id = :transaction_id, '
            . 'status = :status, updated_at = :updated_at WHERE intent_key = :intent_key'
        );
        $statement->execute([
            'transaction_id' => $transactionId,
            'status' => 'linked',
            'updated_at' => gmdate('c'),
            'intent_key' => $key,
        ]);
    }

    public function find(string $key): ?array
    {
        $statement = $this->pdo->prepare('SELECT * FROM sisp_payment_intents WHERE intent_key = :intent_key LIMIT 1');
        $statement->execute(['intent_key' => $key]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }
}
