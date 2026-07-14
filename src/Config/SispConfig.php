<?php

declare(strict_types=1);

namespace Kowts\Sisp\Config;

use Kowts\Sisp\Contract\TransactionStore;
use Kowts\Sisp\Domain\ValueObject\SispCredentials;
use PDO;

final class SispConfig
{
    private SispCredentials $credentials;
    private string $transactionCode;
    private ?PDO $pdo;
    private ?TransactionStore $transactionStore;
    private bool $autoMigrate;

    /**
     * @param array<string,mixed> $data
     */
    private function __construct(array $data)
    {
        $this->credentials = new SispCredentials($data);
        $this->transactionCode = (string) ($data['transactionCode'] ?? '1');
        $this->pdo = isset($data['pdo']) && $data['pdo'] instanceof PDO ? $data['pdo'] : null;
        $this->transactionStore = isset($data['transactionStore'])
            && $data['transactionStore'] instanceof TransactionStore
            ? $data['transactionStore']
            : null;
        $this->autoMigrate = (bool) ($data['autoMigrate'] ?? true);
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

    public function pdo(): ?PDO
    {
        return $this->pdo;
    }

    public function transactionStore(): ?TransactionStore
    {
        return $this->transactionStore;
    }

    public function autoMigrate(): bool
    {
        return $this->autoMigrate;
    }
}
