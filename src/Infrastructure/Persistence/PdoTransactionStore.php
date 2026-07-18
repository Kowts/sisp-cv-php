<?php

declare(strict_types=1);

namespace Kowts\Sisp\Infrastructure\Persistence;

use Kowts\Sisp\Contract\TransactionStore;
use Kowts\Sisp\Domain\TransactionStatus;
use Kowts\Sisp\Domain\ValueObject\CallbackPayload;
use Kowts\Sisp\Domain\ValueObject\PaymentRequest;
use Kowts\Sisp\Domain\ValueObject\TransactionRecord;
use PDO;

final class PdoTransactionStore implements TransactionStore
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

    public function storePaymentRequest(PaymentRequest $request): TransactionRecord
    {
        $now = gmdate('c');
        $payload = $this->encodePayload($request->toSafeStorageFields());

        $this->pdo->beginTransaction();

        try {
            $statement = $this->pdo->prepare($this->insertTransactionSql());
            $statement->execute([
                'merchant_ref' => $request->merchantRef,
                'merchant_session' => $request->merchantSession,
                'amount' => (string) $request->amount,
                'currency' => $request->currency,
                'transaction_code' => $request->transactionCode,
                'status' => TransactionStatus::PENDING,
                'payload' => $payload,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $id = $this->insertedTransactionId($statement);

            $attempt = $this->pdo->prepare(
                'INSERT INTO sisp_transaction_attempts '
                . '(transaction_id, merchant_ref, merchant_session, status, payload, created_at, updated_at) '
                . 'VALUES (:transaction_id, :merchant_ref, :merchant_session, :status, :payload, '
                . ':created_at, :updated_at)'
            );
            $attempt->execute([
                'transaction_id' => $id,
                'merchant_ref' => $request->merchantRef,
                'merchant_session' => $request->merchantSession,
                'status' => TransactionStatus::PENDING,
                'payload' => $payload,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $this->pdo->commit();

            return $this->findById($id);
        } catch (\Throwable $exception) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $exception;
        }
    }

    public function findByMerchantIdentifiers(string $merchantRef, string $merchantSession): ?TransactionRecord
    {
        $statement = $this->pdo->prepare(
            $this->selectOnePrefix() . ' FROM sisp_transactions '
            . 'WHERE merchant_ref = :merchant_ref AND merchant_session = :merchant_session'
            . $this->selectOneSuffix()
        );
        $statement->execute(['merchant_ref' => $merchantRef, 'merchant_session' => $merchantSession]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->recordFromRow($row) : null;
    }

    public function applyCallback(
        TransactionRecord $transaction,
        CallbackPayload $payload,
        string $status
    ): TransactionRecord {
        $now = gmdate('c');
        $callbackPayload = $payload->toSafeStorageFields();
        $encoded = $this->encodePayload(
            array_merge($transaction->payload, ['callback' => $callbackPayload])
        );

        $this->pdo->beginTransaction();

        try {
            $statement = $this->pdo->prepare(
                'UPDATE sisp_transactions SET status = :status, '
                . 'gateway_transaction_id = :gateway_transaction_id, payload = :payload, '
                . 'updated_at = :updated_at WHERE id = :id AND status = :pending_status'
            );
            $statement->execute([
                'status' => $status,
                'gateway_transaction_id' => (string) $payload->transactionID,
                'payload' => $encoded,
                'updated_at' => $now,
                'id' => $transaction->id,
                'pending_status' => TransactionStatus::PENDING,
            ]);

            if ($statement->rowCount() !== 1) {
                $this->pdo->rollBack();

                return $this->findById($transaction->id);
            }

            $attempt = $this->pdo->prepare(
                'UPDATE sisp_transaction_attempts SET status = :status, '
                . 'gateway_transaction_id = :gateway_transaction_id, payload = :payload, '
                . 'updated_at = :updated_at '
                . 'WHERE transaction_id = :transaction_id AND merchant_ref = :merchant_ref '
                . 'AND merchant_session = :merchant_session'
            );
            $attempt->execute([
                'status' => $status,
                'gateway_transaction_id' => (string) $payload->transactionID,
                'payload' => $this->encodePayload($callbackPayload),
                'updated_at' => $now,
                'transaction_id' => $transaction->id,
                'merchant_ref' => $transaction->merchantRef,
                'merchant_session' => $transaction->merchantSession,
            ]);

            $this->pdo->commit();

            return $this->findById($transaction->id);
        } catch (\Throwable $exception) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $exception;
        }
    }

    private function findById(int $id): TransactionRecord
    {
        $statement = $this->pdo->prepare('SELECT * FROM sisp_transactions WHERE id = :id');
        $statement->execute(['id' => $id]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (! $row) {
            throw new \RuntimeException('SISP transaction not found after persistence.');
        }

        return $this->recordFromRow($row);
    }

    private function insertTransactionSql(): string
    {
        $columns = '(merchant_ref, merchant_session, amount, currency, transaction_code, status, payload, '
            . 'created_at, updated_at)';
        $values = 'VALUES (:merchant_ref, :merchant_session, :amount, :currency, :transaction_code, :status, '
            . ':payload, :created_at, :updated_at)';

        if ($this->driverName() === 'sqlsrv') {
            return 'INSERT INTO sisp_transactions ' . $columns . ' OUTPUT INSERTED.id ' . $values;
        }

        $sql = 'INSERT INTO sisp_transactions ' . $columns . ' ' . $values;

        return $this->driverName() === 'pgsql' ? $sql . ' RETURNING id' : $sql;
    }

    private function insertedTransactionId(\PDOStatement $statement): int
    {
        if (in_array($this->driverName(), ['pgsql', 'sqlsrv'], true)) {
            $id = $statement->fetchColumn();

            if ($id === false) {
                throw new \RuntimeException('O driver PDO não devolveu o ID da transação SISP criada.');
            }

            return (int) $id;
        }

        return (int) $this->pdo->lastInsertId();
    }

    private function selectOnePrefix(): string
    {
        return $this->driverName() === 'sqlsrv' ? 'SELECT TOP 1 *' : 'SELECT *';
    }

    private function selectOneSuffix(): string
    {
        return $this->driverName() === 'sqlsrv' ? '' : ' LIMIT 1';
    }

    private function driverName(): string
    {
        return (string) $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    }

    /**
     * @param array<string,mixed> $payload
     */
    private function encodePayload(array $payload): string
    {
        return json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES);
    }

    /**
     * @param array<string,mixed> $row
     */
    private function recordFromRow(array $row): TransactionRecord
    {
        $payload = [];

        if (isset($row['payload']) && is_string($row['payload']) && $row['payload'] !== '') {
            $decoded = json_decode($row['payload'], true);
            $payload = is_array($decoded) ? $decoded : [];
        }

        return new TransactionRecord(
            (int) $row['id'],
            (string) $row['merchant_ref'],
            (string) $row['merchant_session'],
            (string) $row['amount'],
            (string) $row['currency'],
            (string) $row['transaction_code'],
            (string) $row['status'],
            isset($row['gateway_transaction_id']) ? (string) $row['gateway_transaction_id'] : null,
            $payload
        );
    }
}
