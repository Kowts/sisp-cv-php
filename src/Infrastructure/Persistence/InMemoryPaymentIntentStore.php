<?php

declare(strict_types=1);

namespace Kowts\Sisp\Infrastructure\Persistence;

use Kowts\Sisp\Contract\PaymentIntentStore;

final class InMemoryPaymentIntentStore implements PaymentIntentStore
{
    /** @var array<string,array<string,mixed>> */
    private array $intents = [];

    public function reserve(string $key, string $status = 'reserved'): void
    {
        $this->intents[$key] = ['key' => $key, 'status' => $status, 'transaction_id' => null];
    }

    public function link(string $key, int $transactionId): void
    {
        $this->intents[$key] = ['key' => $key, 'status' => 'linked', 'transaction_id' => $transactionId];
    }

    public function find(string $key): ?array
    {
        return $this->intents[$key] ?? null;
    }
}
