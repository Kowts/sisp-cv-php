<?php

declare(strict_types=1);

namespace Kowts\Sisp\Infrastructure\Persistence;

use PDO;

final class SispSchema
{
    public static function migrate(PDO $pdo): void
    {
        foreach (self::statements() as $statement) {
            $pdo->exec($statement);
        }
    }

    /**
     * @return list<string>
     */
    public static function statements(): array
    {
        return [
            'CREATE TABLE IF NOT EXISTS sisp_transactions (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                merchant_ref VARCHAR(64) NOT NULL,
                merchant_session VARCHAR(64) NOT NULL,
                amount VARCHAR(32) NOT NULL,
                currency VARCHAR(8) NOT NULL,
                transaction_code VARCHAR(8) NOT NULL,
                status VARCHAR(32) NOT NULL,
                gateway_transaction_id VARCHAR(64) NULL,
                payload TEXT NULL,
                created_at VARCHAR(32) NOT NULL,
                updated_at VARCHAR(32) NOT NULL
            )',
            'CREATE UNIQUE INDEX IF NOT EXISTS sisp_transactions_identifiers_unique ON sisp_transactions (merchant_ref, merchant_session)',
            'CREATE TABLE IF NOT EXISTS sisp_transaction_attempts (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                transaction_id INTEGER NOT NULL,
                merchant_ref VARCHAR(64) NOT NULL,
                merchant_session VARCHAR(64) NOT NULL,
                status VARCHAR(32) NOT NULL,
                gateway_transaction_id VARCHAR(64) NULL,
                payload TEXT NULL,
                created_at VARCHAR(32) NOT NULL,
                updated_at VARCHAR(32) NOT NULL
            )',
            'CREATE TABLE IF NOT EXISTS sisp_payment_intents (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                intent_key VARCHAR(128) NOT NULL UNIQUE,
                transaction_id INTEGER NULL,
                status VARCHAR(32) NOT NULL,
                created_at VARCHAR(32) NOT NULL,
                updated_at VARCHAR(32) NOT NULL
            )',
            'CREATE TABLE IF NOT EXISTS sisp_transaction_logs (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                transaction_id INTEGER NULL,
                level VARCHAR(16) NOT NULL,
                message VARCHAR(255) NOT NULL,
                context TEXT NULL,
                created_at VARCHAR(32) NOT NULL
            )',
            'CREATE TABLE IF NOT EXISTS sisp_request_metadata (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                transaction_id INTEGER NULL,
                ip_address VARCHAR(64) NULL,
                user_agent TEXT NULL,
                payload TEXT NULL,
                created_at VARCHAR(32) NOT NULL
            )',
            'CREATE TABLE IF NOT EXISTS sisp_blacklist (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                type VARCHAR(32) NOT NULL,
                value VARCHAR(255) NOT NULL,
                reason VARCHAR(255) NULL,
                expires_at VARCHAR(32) NULL,
                created_at VARCHAR(32) NOT NULL
            )',
            'CREATE TABLE IF NOT EXISTS sisp_rate_limits (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                scope VARCHAR(64) NOT NULL,
                identifier VARCHAR(255) NOT NULL,
                attempts INTEGER NOT NULL DEFAULT 0,
                resets_at VARCHAR(32) NOT NULL,
                created_at VARCHAR(32) NOT NULL,
                updated_at VARCHAR(32) NOT NULL
            )',
        ];
    }
}
