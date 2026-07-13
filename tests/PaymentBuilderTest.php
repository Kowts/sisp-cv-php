<?php

declare(strict_types=1);

namespace Kowts\Sisp\Tests;

use Kowts\Sisp\Config\SispConfig;
use Kowts\Sisp\SispFactory;
use PHPUnit\Framework\TestCase;

final class PaymentBuilderTest extends TestCase
{
    public function testBuildsSignedPaymentRequest(): void
    {
        $sisp = SispFactory::create(SispConfig::fromArray([
            'posId' => '90051',
            'posAutCode' => 'secret',
            'url' => 'https://gateway.example/pay',
            'urlMerchantResponse' => 'https://app.example/callback',
        ]));

        $request = $sisp->payment()
            ->amount('1500')
            ->merchantRef('R20260612100000')
            ->merchantSession('S20260612100000')
            ->timeStamp('2026-06-12 10:00:00')
            ->build();

        self::assertSame('90051', $request->posID);
        self::assertSame('R20260612100000', $request->merchantRef);
        self::assertNotSame('', $request->fingerprint);
        self::assertStringContainsString('FingerPrint=', $sisp->gatewayFormAction($request));
    }
}
