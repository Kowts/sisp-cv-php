<?php

declare(strict_types=1);

namespace Akira\SispPhp\Support;

use InvalidArgumentException;
use RangeException;

final class SispAmount
{
    private const DECIMAL_PATTERN = '/^[+-]?(?:\d+(?:\.\d*)?|\.\d+)$/';
    private const MAX_SAFE_THOUSANDTHS = 9007199254740991;

    /**
     * @param float|int|string $amount
     */
    public static function toThousandths($amount): int
    {
        $decimal = self::decimalString($amount);

        return self::decimalStringToThousandths($decimal);
    }

    /**
     * @param float|int|string $amount
     */
    public static function toCents($amount): int
    {
        $value = self::toThousandths($amount) / 10;

        return $value < 0 ? (int) -round(abs($value)) : (int) round($value);
    }

    /**
     * @param float|int|string $cents
     */
    public static function fromCents($cents): float
    {
        if (! is_numeric($cents)) {
            return 0.0;
        }

        return ((float) $cents) / 100;
    }

    /**
     * @param float|int|string $amount
     */
    private static function decimalString($amount): string
    {
        if (is_int($amount)) {
            return (string) $amount;
        }

        if (is_float($amount)) {
            if (! is_finite($amount)) {
                throw new InvalidArgumentException('Invalid SISP amount. Use a dot as the decimal separator.');
            }

            return floor($amount) === $amount
                ? sprintf('%.0f', $amount)
                : rtrim(rtrim(sprintf('%.10F', $amount), '0'), '.');
        }

        $decimal = trim((string) $amount);

        if ($decimal === '' || preg_match(self::DECIMAL_PATTERN, $decimal) !== 1) {
            throw new InvalidArgumentException('Invalid SISP amount. Use a dot as the decimal separator.');
        }

        return $decimal;
    }

    private static function decimalStringToThousandths(string $decimal): int
    {
        $sign = 1;

        if (strpos($decimal, '-') === 0) {
            $sign = -1;
            $decimal = substr($decimal, 1);
        }

        if (strpos($decimal, '+') === 0) {
            $decimal = substr($decimal, 1);
        }

        $parts = explode('.', $decimal, 2);
        $units = $parts[0] === '' ? '0' : $parts[0];
        $fraction = str_pad($parts[1] ?? '', 4, '0');

        $thousandths = ((int) $units * 1000) + (int) substr($fraction, 0, 3);

        if ((int) $fraction[3] >= 5) {
            $thousandths++;
        }

        $signed = $sign * $thousandths;

        if (abs($signed) > self::MAX_SAFE_THOUSANDTHS) {
            throw new RangeException('SISP amount exceeds the supported range.');
        }

        return $signed;
    }
}
