<?php

declare(strict_types=1);

namespace Kowts\Sisp\Domain\ValueObject;

final class TransactionRecord
{
    public int $id;
    public string $merchantRef;
    public string $merchantSession;
    /** @var float|int|string */
    public $amount;
    public string $currency;
    public string $transactionCode;
    public string $status;
    public ?string $gatewayTransactionId;
    /** @var array<string,mixed> */
    public array $payload;

    /**
     * @param float|int|string $amount
     * @param array<string,mixed> $payload
     */
    public function __construct(
        int $id,
        string $merchantRef,
        string $merchantSession,
        $amount,
        string $currency,
        string $transactionCode,
        string $status,
        ?string $gatewayTransactionId = null,
        array $payload = []
    ) {
        $this->id = $id;
        $this->merchantRef = $merchantRef;
        $this->merchantSession = $merchantSession;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->transactionCode = $transactionCode;
        $this->status = $status;
        $this->gatewayTransactionId = $gatewayTransactionId;
        $this->payload = $payload;
    }
}
