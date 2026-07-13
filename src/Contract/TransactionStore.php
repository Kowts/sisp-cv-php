<?php

declare(strict_types=1);

namespace Kowts\Sisp\Contract;

use Kowts\Sisp\Domain\ValueObject\CallbackPayload;
use Kowts\Sisp\Domain\ValueObject\PaymentRequest;
use Kowts\Sisp\Domain\ValueObject\TransactionRecord;

interface TransactionStore
{
    public function storePaymentRequest(PaymentRequest $request): TransactionRecord;

    public function findByMerchantIdentifiers(string $merchantRef, string $merchantSession): ?TransactionRecord;

    public function applyCallback(TransactionRecord $transaction, CallbackPayload $payload, string $status): TransactionRecord;
}
