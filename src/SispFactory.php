<?php

declare(strict_types=1);

namespace Kowts\Sisp;

use Kowts\Sisp\Config\SispConfig;

final class SispFactory
{
    public static function create(SispConfig $config): Sisp
    {
        return new Sisp($config->credentials(), $config->transactionCode());
    }
}
