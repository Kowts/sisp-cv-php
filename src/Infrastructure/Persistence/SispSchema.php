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
        $driver = self::normaliseDriver($driver);
        $primaryKey = self::primaryKeyColumn($driver);
        $transactionId = self::transactionIdColumn($driver);
        $text = self::textColumn($driver);

        return [
            self::createTable(
                $driver,
                'sisp_transactions',
                'id ' . $primaryKey . ', '
                . 'merchant_ref VARCHAR(64) NOT NULL, '
                . 'merchant_session VARCHAR(64) NOT NULL, '
                . 'amount VARCHAR(32) NOT NULL, '
                . 'currency VARCHAR(8) NOT NULL, '
                . 'transaction_code VARCHAR(8) NOT NULL, '
                . 'status VARCHAR(32) NOT NULL, '
                . 'gateway_transaction_id VARCHAR(64) NULL, '
                . 'payload ' . $text . ' NULL, '
                . 'created_at VARCHAR(32) NOT NULL, '
                . 'updated_at VARCHAR(32) NOT NULL, '
                . 'UNIQUE (merchant_ref, merchant_session)'
            ),
            self::createTable(
                $driver,
                'sisp_transaction_attempts',
                'id ' . $primaryKey . ', '
                . 'transaction_id ' . $transactionId . ' NOT NULL, '
                . 'merchant_ref VARCHAR(64) NOT NULL, '
                . 'merchant_session VARCHAR(64) NOT NULL, '
                . 'status VARCHAR(32) NOT NULL, '
                . 'gateway_transaction_id VARCHAR(64) NULL, '
                . 'payload ' . $text . ' NULL, '
                . 'created_at VARCHAR(32) NOT NULL, '
                . 'updated_at VARCHAR(32) NOT NULL'
            ),
            self::createTable(
                $driver,
                'sisp_payment_intents',
                'id ' . $primaryKey . ', '
                . 'intent_key VARCHAR(128) NOT NULL UNIQUE, '
                . 'transaction_id ' . $transactionId . ' NULL, '
                . 'status VARCHAR(32) NOT NULL, '
                . 'created_at VARCHAR(32) NOT NULL, '
                . 'updated_at VARCHAR(32) NOT NULL'
            ),
            self::createTable(
                $driver,
                'sisp_transaction_logs',
                'id ' . $primaryKey . ', '
                . 'transaction_id ' . $transactionId . ' NULL, '
                . 'level VARCHAR(16) NOT NULL, '
                . 'message VARCHAR(255) NOT NULL, '
                . 'context ' . $text . ' NULL, '
                . 'created_at VARCHAR(32) NOT NULL'
            ),
            self::createTable(
                $driver,
                'sisp_request_metadata',
                'id ' . $primaryKey . ', '
                . 'transaction_id ' . $transactionId . ' NULL, '
                . 'ip_address VARCHAR(64) NULL, '
                . 'user_agent ' . $text . ' NULL, '
                . 'payload ' . $text . ' NULL, '
                . 'created_at VARCHAR(32) NOT NULL'
            ),
            self::createTable(
                $driver,
                'sisp_blacklist',
                'id ' . $primaryKey . ', '
                . 'type VARCHAR(32) NOT NULL, '
                . 'value VARCHAR(255) NOT NULL, '
                . 'reason VARCHAR(255) NULL, '
                . 'expires_at VARCHAR(32) NULL, '
                . 'created_at VARCHAR(32) NOT NULL'
            ),
            self::createTable(
                $driver,
                'sisp_rate_limits',
                'id ' . $primaryKey . ', '
                . 'scope VARCHAR(64) NOT NULL, '
                . 'identifier VARCHAR(255) NOT NULL, '
                . 'attempts INTEGER NOT NULL DEFAULT 0, '
                . 'resets_at VARCHAR(32) NOT NULL, '
                . 'created_at VARCHAR(32) NOT NULL, '
                . 'updated_at VARCHAR(32) NOT NULL'
            ),
        ];
    }

    private static function primaryKeyColumn(string $driver): string
    {
        return match (self::normaliseDriver($driver)) {
            'sqlite' => 'INTEGER PRIMARY KEY AUTOINCREMENT',
            'mysql' => 'BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY',
            'pgsql' => 'BIGSERIAL PRIMARY KEY',
            'sqlsrv' => 'BIGINT IDENTITY(1,1) PRIMARY KEY',
            default => throw new \LogicException('Driver PDO SISP inesperado.'),
        };
    }

    private static function transactionIdColumn(string $driver): string
    {
        return match (self::normaliseDriver($driver)) {
            'sqlite' => 'INTEGER',
            'mysql' => 'BIGINT UNSIGNED',
            'pgsql', 'sqlsrv' => 'BIGINT',
            default => throw new \LogicException('Driver PDO SISP inesperado.'),
        };
    }

    private static function textColumn(string $driver): string
    {
        return self::normaliseDriver($driver) === 'sqlsrv' ? 'VARCHAR(MAX)' : 'TEXT';
    }

    private static function createTable(string $driver, string $table, string $columns): string
    {
        if (self::normaliseDriver($driver) === 'sqlsrv') {
            return 'IF OBJECT_ID(N\'dbo.' . $table . '\', N\'U\') IS NULL '
                . 'BEGIN CREATE TABLE dbo.' . $table . ' (' . $columns . ') END';
        }

        return 'CREATE TABLE IF NOT EXISTS ' . $table . ' (' . $columns . ')';
    }

    private static function normaliseDriver(string $driver): string
    {
        $driver = strtolower(trim($driver));

        if (! in_array($driver, ['sqlite', 'mysql', 'pgsql', 'sqlsrv'], true)) {
            throw new InvalidArgumentException(
                'SISP PDO suporta SQLite, MySQL/MariaDB, PostgreSQL e SQL Server.'
            );
        }

        return $driver;
    }
}
