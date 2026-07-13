<?php

declare(strict_types=1);

namespace Kowts\Sisp\Contract;

interface PaymentIntentStore
{
    public function reserve(string $key, string $status = 'reserved'): void;

    public function link(string $key, int $transactionId): void;

    /**
     * @return array<string,mixed>|null
     */
    public function find(string $key): ?array;
}
