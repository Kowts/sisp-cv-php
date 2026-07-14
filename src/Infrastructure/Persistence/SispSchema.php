<?php

declare(strict_types=1);

namespace Kowts\Sisp\Infrastructure\Persistence;

use InvalidArgumentException;
use PDO;

final class SispSchema
{
    public static function migrate(PDO $pdo): void
    {
        foreach (self::statements((string) $pdo->getAttribute(PDO::ATTR_DRIVER_NAME)) as $statement) {
            $pdo->exec($statement);
        }
    }

    /**
     * @return list<string>
     */
    public static function statements(string $driver = 'sqlite'): array
    {
        $primaryKey = self::primaryKeyColumn($driver);
        $transactionId = self::transactionIdColumn($driver);

        return [
            'CREATE TABLE IF NOT EXISTS sisp_transactions ('
                . 'id ' . $primaryKey . ', '
                . 'merchant_ref VARCHAR(64) NOT NULL, '
                . 'merchant_session VARCHAR(64) NOT NULL, '
                . 'amount VARCHAR(32) NOT NULL, '
                . 'currency VARCHAR(8) NOT NULL, '
                . 'transaction_code VARCHAR(8) NOT NULL, '
                . 'status VARCHAR(32) NOT NULL, '
                . 'gateway_transaction_id VARCHAR(64) NULL, '
                . 'payload TEXT NULL, '
                . 'created_at VARCHAR(32) NOT NULL, '
                . 'updated_at VARCHAR(32) NOT NULL, '
                . 'UNIQUE (merchant_ref, merchant_session)'
                . ')',
            'CREATE TABLE IF NOT EXISTS sisp_transaction_attempts ('
                . 'id ' . $primaryKey . ', '
                . 'transaction_id ' . $transactionId . ' NOT NULL, '
                . 'merchant_ref VARCHAR(64) NOT NULL, '
                . 'merchant_session VARCHAR(64) NOT NULL, '
                . 'status VARCHAR(32) NOT NULL, '
                . 'gateway_transaction_id VARCHAR(64) NULL, '
                . 'payload TEXT NULL, '
                . 'created_at VARCHAR(32) NOT NULL, '
                . 'updated_at VARCHAR(32) NOT NULL'
                . ')',
            'CREATE TABLE IF NOT EXISTS sisp_payment_intents ('
                . 'id ' . $primaryKey . ', '
                . 'intent_key VARCHAR(128) NOT NULL UNIQUE, '
                . 'transaction_id ' . $transactionId . ' NULL, '
                . 'status VARCHAR(32) NOT NULL, '
                . 'created_at VARCHAR(32) NOT NULL, '
                . 'updated_at VARCHAR(32) NOT NULL'
                . ')',
            'CREATE TABLE IF NOT EXISTS sisp_transaction_logs ('
                . 'id ' . $primaryKey . ', '
                . 'transaction_id ' . $transactionId . ' NULL, '
                . 'level VARCHAR(16) NOT NULL, '
                . 'message VARCHAR(255) NOT NULL, '
                . 'context TEXT NULL, '
                . 'created_at VARCHAR(32) NOT NULL'
                . ')',
            'CREATE TABLE IF NOT EXISTS sisp_request_metadata ('
                . 'id ' . $primaryKey . ', '
                . 'transaction_id ' . $transactionId . ' NULL, '
                . 'ip_address VARCHAR(64) NULL, '
                . 'user_agent TEXT NULL, '
                . 'payload TEXT NULL, '
                . 'created_at VARCHAR(32) NOT NULL'
                . ')',
            'CREATE TABLE IF NOT EXISTS sisp_blacklist ('
                . 'id ' . $primaryKey . ', '
                . 'type VARCHAR(32) NOT NULL, '
                . 'value VARCHAR(255) NOT NULL, '
                . 'reason VARCHAR(255) NULL, '
                . 'expires_at VARCHAR(32) NULL, '
                . 'created_at VARCHAR(32) NOT NULL'
                . ')',
            'CREATE TABLE IF NOT EXISTS sisp_rate_limits ('
                . 'id ' . $primaryKey . ', '
                . 'scope VARCHAR(64) NOT NULL, '
                . 'identifier VARCHAR(255) NOT NULL, '
                . 'attempts INTEGER NOT NULL DEFAULT 0, '
                . 'resets_at VARCHAR(32) NOT NULL, '
                . 'created_at VARCHAR(32) NOT NULL, '
                . 'updated_at VARCHAR(32) NOT NULL'
                . ')',
        ];
    }

    private static function primaryKeyColumn(string $driver): string
    {
        return match (self::normaliseDriver($driver)) {
            'sqlite' => 'INTEGER PRIMARY KEY AUTOINCREMENT',
            'mysql' => 'BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY',
            'pgsql' => 'BIGSERIAL PRIMARY KEY',
        };
    }

    private static function transactionIdColumn(string $driver): string
    {
        return match (self::normaliseDriver($driver)) {
            'sqlite' => 'INTEGER',
            'mysql' => 'BIGINT UNSIGNED',
            'pgsql' => 'BIGINT',
        };
    }

    private static function normaliseDriver(string $driver): string
    {
        $driver = strtolower(trim($driver));

        if (! in_array($driver, ['sqlite', 'mysql', 'pgsql'], true)) {
            throw new InvalidArgumentException(
                'SISP PDO suporta SQLite, MySQL/MariaDB e PostgreSQL.'
            );
        }

        return $driver;
    }
}
