<?php

declare(strict_types=1);

namespace Kowts\Sisp\Domain;

final class TransactionStatus
{
    public const PENDING = 'pending';
    public const COMPLETED = 'completed';
    public const FAILED = 'failed';
    public const CANCELLED = 'cancelled';
    public const REFUNDED = 'refunded';

    private function __construct()
    {
    }
}
