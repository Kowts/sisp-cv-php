<?php

declare(strict_types=1);

namespace Kowts\Sisp\Config;

use Kowts\Sisp\Domain\ValueObject\SispCredentials;

final class SispConfig
{
    private SispCredentials $credentials;
    private string $transactionCode;

    /**
     * @param array<string,mixed> $data
     */
    private function __construct(array $data)
    {
        $this->credentials = new SispCredentials($data);
        $this->transactionCode = (string) ($data['transactionCode'] ?? '1');
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function credentials(): SispCredentials
    {
        return $this->credentials;
    }

    public function transactionCode(): string
    {
        return $this->transactionCode;
    }
}
