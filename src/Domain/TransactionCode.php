<?php

declare(strict_types=1);

namespace Kowts\Sisp\Domain;

final class TransactionCode
{
    public const PURCHASE = '1';
    public const REFUND = '4';

    private function __construct()
    {
    }
}
