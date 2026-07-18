<?php

declare(strict_types=1);

namespace Kowts\Sisp\Tests;

use DateTimeImmutable;
use Kowts\Sisp\Application\Action\BuildPurchaseRequest;
use PHPUnit\Framework\TestCase;

final class ThreeDsRequestTest extends TestCase
{
    public function testUsesCustomerBillingDataForShippingWhenNoSeparateAddressExists(): void
    {
        $request = BuildPurchaseRequest::handle([
            'email' => 'cliente@example.test',
            'country' => 'PT',
            'city' => 'Lisboa',
            'address' => 'Rua do Exemplo, 1',
            'postalCode' => '1000-001',
            'phone' => '912345678',
        ], new DateTimeImmutable('2026-07-18 10:00:00'));

        $payload = json_decode((string) base64_decode($request), true, 512, JSON_THROW_ON_ERROR);

        self::assertSame('Lisboa', $payload['billAddrCity']);
        self::assertSame($payload['billAddrCity'], $payload['shipAddrCity']);
        self::assertSame($payload['billAddrCountry'], $payload['shipAddrCountry']);
        self::assertSame($payload['billAddrLine1'], $payload['shipAddrLine1']);
        self::assertSame($payload['billAddrPostCode'], $payload['shipAddrPostCode']);
    }
}
