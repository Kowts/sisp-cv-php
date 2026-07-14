<?php

declare(strict_types=1);

namespace Kowts\Sisp\Support;

use DateTimeImmutable;
use DateTimeInterface;

final class Generators
{
    private const MAX_IDENTIFIER_LENGTH = 15;
    private const ALPHABET = '0123456789abcdefghijklmnopqrstuvwxyz';

    public static function merchantReference(?DateTimeInterface $date = null): string
    {
        return self::merchantIdentifier('R', $date);
    }

    public static function merchantSession(?DateTimeInterface $date = null): string
    {
        return self::merchantIdentifier('S', $date);
    }

    public static function timeStamp(?DateTimeInterface $date = null): string
    {
        $date = $date ?: new DateTimeImmutable();

        return $date->format('Y-m-d H:i:s');
    }

    private static function merchantIdentifier(string $prefix, ?DateTimeInterface $date): string
    {
        $date = $date ?: new DateTimeImmutable();
        $time = base_convert((string) ($date->getTimestamp() * 1000), 10, 36);
        $randomLength = max(2, self::MAX_IDENTIFIER_LENGTH - strlen($prefix) - strlen($time));

        return substr($prefix . $time . self::randomAlphanumeric($randomLength), 0, self::MAX_IDENTIFIER_LENGTH);
    }

    private static function randomAlphanumeric(int $length): string
    {
        $identifier = '';
        $max = strlen(self::ALPHABET) - 1;

        for ($index = 0; $index < $length; $index++) {
            $identifier .= self::ALPHABET[random_int(0, $max)];
        }

        return $identifier;
    }
}
