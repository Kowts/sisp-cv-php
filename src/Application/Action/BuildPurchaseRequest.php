<?php

declare(strict_types=1);

namespace Kowts\Sisp\Application\Action;

use Kowts\Sisp\Support\CountryCodeMapper;
use DateTimeImmutable;
use DateTimeInterface;

final class BuildPurchaseRequest
{
    /**
     * @param array<string,string|null> $customer
     */
    public static function handle(array $customer, ?DateTimeInterface $now = null): string
    {
        $now = $now ?: new DateTimeImmutable();
        $date = $now->format('Ymd');

        $payload = [
            'acctID' => 'x',
            'acctInfo' => [
                'chAccAgeInd' => '05',
                'chAccChange' => $date,
                'chAccDate' => $date,
                'chAccPwChange' => $date,
                'chAccPwChangeInd' => '05',
                'suspiciousAccActivity' => '01',
            ],
            'email' => (string) ($customer['email'] ?? ''),
            'addrMatch' => 'N',
            'billAddrCity' => (string) ($customer['city'] ?? ''),
            'billAddrCountry' => CountryCodeMapper::toNumeric((string) ($customer['country'] ?? 'CV')),
            'billAddrLine1' => (string) ($customer['address'] ?? ''),
            'billAddrLine2' => '',
            'billAddrLine3' => '',
            'billAddrPostCode' => (string) ($customer['postalCode'] ?? ''),
            'billAddrState' => '',
            'shipAddrCity' => 'City',
            'shipAddrCountry' => '132',
            'shipAddrLine1' => '000',
            'shipAddrPostCode' => '000',
            'shipAddrState' => '',
            'workPhone' => ['cc' => '238', 'subscriber' => '0000000'],
            'mobilePhone' => ['cc' => '238', 'subscriber' => $customer['phone'] ?? '0000000'],
        ];

        return base64_encode((string) json_encode($payload, JSON_UNESCAPED_SLASHES));
    }
}
