<?php

declare(strict_types=1);

namespace Kowts\Sisp\Tests;

use Kowts\Sisp\Bridge\Yii2\SispComponent;
use Kowts\Sisp\Sisp;
use PHPUnit\Framework\TestCase;

final class Yii2BridgeTest extends TestCase
{
    public function testComponentBuildsClient(): void
    {
        $component = new SispComponent();
        $component->config = [
            'posId' => '90051',
            'posAutCode' => 'secret',
            'url' => 'https://gateway.example/pay',
            'urlMerchantResponse' => 'https://app.example/callback',
        ];

        self::assertInstanceOf(Sisp::class, $component->getClient());
    }
}
