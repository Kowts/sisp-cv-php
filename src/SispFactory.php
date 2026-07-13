<?php

declare(strict_types=1);

namespace Kowts\Sisp;

use Kowts\Sisp\Config\SispConfig;
use Kowts\Sisp\Infrastructure\Persistence\PdoTransactionStore;

final class SispFactory
{
    public static function create(SispConfig $config): Sisp
    {
        $store = $config->transactionStore();

        if ($store === null && $config->pdo() !== null) {
            $store = new PdoTransactionStore($config->pdo(), $config->autoMigrate());
        }

        return new Sisp($config->credentials(), $config->transactionCode(), $store);
    }
}
