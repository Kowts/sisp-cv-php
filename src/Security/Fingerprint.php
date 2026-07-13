<?php

declare(strict_types=1);

namespace Akira\SispPhp\Security;

use Akira\SispPhp\Support\SispAmount;
use Akira\SispPhp\ValueObjects\CallbackPayload;

final class Fingerprint
{
    public static function sha512Base64(string $content): string
    {
        return base64_encode(hash('sha512', $content, true));
    }

    public static function computeToken(string $posAutCode): string
    {
        return self::sha512Base64($posAutCode);
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function payment(string $token, array $data): string
    {
        $content = $token
            .($data['timeStamp'] ?? '')
            .SispAmount::toThousandths($data['amount'] ?? 0)
            .($data['merchantRef'] ?? '')
            .($data['merchantSession'] ?? '')
            .($data['posID'] ?? '')
            .($data['currency'] ?? '')
            .($data['transactionCode'] ?? '');

        return self::sha512Base64($content);
    }

    public static function callback(string $token, CallbackPayload $payload): string
    {
        $fields = [
            $token,
            $payload->messageType,
            $payload->clearingPeriod,
            (string) $payload->transactionID,
            $payload->merchantRef,
            $payload->merchantSession,
            (string) SispAmount::toThousandths($payload->amount),
            $payload->messageID,
            $payload->pan,
            $payload->merchantResponse,
            $payload->timeStamp,
            $payload->reference,
            $payload->entityCode,
            $payload->clientReceipt,
            $payload->additionalErrorMessage,
            $payload->reloadCode,
        ];

        return self::sha512Base64(implode('', $fields));
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function refund(string $token, array $data): string
    {
        $fields = [
            $token,
            self::trimmed($data['timeStamp'] ?? null),
            (string) SispAmount::toThousandths($data['amount'] ?? 0),
            self::trimmed($data['merchantRef'] ?? null),
            self::trimmed($data['merchantSession'] ?? null),
            self::trimmed($data['posID'] ?? null),
            self::trimmed($data['currency'] ?? null),
            self::trimmed($data['transactionCode'] ?? null),
            str_pad(self::trimmed($data['clearingPeriod'] ?? null), 4, '0', STR_PAD_LEFT),
            str_pad(self::trimmed($data['transactionID'] ?? null), 8, '0', STR_PAD_LEFT),
        ];

        return self::sha512Base64(implode('', $fields));
    }

    public static function validateCallback(string $token, CallbackPayload $payload): bool
    {
        return self::constantTimeEquals(self::callback($token, $payload), $payload->fingerprint);
    }

    public static function constantTimeEquals(string $expected, string $actual): bool
    {
        return hash_equals(self::compareDigest($expected), self::compareDigest($actual));
    }

    private static function compareDigest(string $value): string
    {
        return hash_hmac('sha256', $value, 'sisp-constant-time-compare', true);
    }

    /**
     * @param mixed $value
     */
    private static function trimmed($value): string
    {
        return $value === null ? '' : trim((string) $value);
    }
}
