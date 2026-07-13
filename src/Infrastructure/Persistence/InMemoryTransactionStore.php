<?php

declare(strict_types=1);

namespace Kowts\Sisp\Infrastructure\Persistence;

use Kowts\Sisp\Contract\TransactionStore;
use Kowts\Sisp\Domain\TransactionStatus;
use Kowts\Sisp\Domain\ValueObject\CallbackPayload;
use Kowts\Sisp\Domain\ValueObject\PaymentRequest;
use Kowts\Sisp\Domain\ValueObject\TransactionRecord;

final class InMemoryTransactionStore implements TransactionStore
{
    /** @var array<int,TransactionRecord> */
    private array $transactions = [];
    private int $nextId = 1;

    public function storePaymentRequest(PaymentRequest $request): TransactionRecord
    {
        $transaction = new TransactionRecord(
            $this->nextId++,
            $request->merchantRef,
            $request->merchantSession,
            $request->amount,
            $request->currency,
            $request->transactionCode,
            TransactionStatus::PENDING,
            null,
            $request->toFormFields()
        );

        $this->transactions[$transaction->id] = $transaction;

        return $transaction;
    }

    public function findByMerchantIdentifiers(string $merchantRef, string $merchantSession): ?TransactionRecord
    {
        foreach ($this->transactions as $transaction) {
            if ($transaction->merchantRef === $merchantRef && $transaction->merchantSession === $merchantSession) {
                return $transaction;
            }
        }

        return null;
    }

    public function applyCallback(TransactionRecord $transaction, CallbackPayload $payload, string $status): TransactionRecord
    {
        $updated = new TransactionRecord(
            $transaction->id,
            $transaction->merchantRef,
            $transaction->merchantSession,
            $transaction->amount,
            $transaction->currency,
            $transaction->transactionCode,
            $status,
            (string) $payload->transactionID,
            array_merge($transaction->payload, ['callback' => $payload->toFormFields()])
        );

        $this->transactions[$updated->id] = $updated;

        return $updated;
    }
}
