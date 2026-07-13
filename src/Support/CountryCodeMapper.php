<?php

declare(strict_types=1);

namespace Kowts\Sisp\Support;

final class CountryCodeMapper
{
    /**
     * @var array<string,string>
     */
    private const ALPHA2_TO_NUMERIC = [
        'CV' => '132',
        'PT' => '620',
        'BR' => '076',
        'ES' => '724',
        'FR' => '250',
        'DE' => '276',
        'GB' => '826',
        'US' => '840',
        'AO' => '024',
        'MZ' => '508',
        'ST' => '678',
        'GW' => '624',
        'NL' => '528',
        'IT' => '380',
        'LU' => '442',
        'CH' => '756',
        'BE' => '056',
        'SN' => '686',
    ];

    public static function toNumeric(string $alpha2Code): string
    {
        $code = strtoupper(trim($alpha2Code));

        if (preg_match('/^\d{3}$/', $code) === 1) {
            return $code;
        }

        return self::ALPHA2_TO_NUMERIC[$code] ?? '132';
    }
}
