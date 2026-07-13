<?php

declare(strict_types=1);

namespace Kowts\Sisp\Domain\ValueObject;

use InvalidArgumentException;

final class SispCredentials
{
    public string $posId;
    public string $posAutCode;
    public string $url;
    public string $merchantId;
    public string $currency;
    public string $languageMessages;
    public string $fingerprintVersion;
    public string $is3DSec;
    public string $urlMerchantResponse;

    /**
     * @param array<string,mixed> $data
     */
    public function __construct(array $data)
    {
        $this->posId = self::required($data, 'posId');
        $this->posAutCode = self::required($data, 'posAutCode');
        $this->url = (string) ($data['url'] ?? '');
        $this->merchantId = (string) ($data['merchantId'] ?? '');
        $this->currency = (string) ($data['currency'] ?? '132');
        $this->languageMessages = (string) ($data['languageMessages'] ?? 'EN');
        $this->fingerprintVersion = (string) ($data['fingerprintVersion'] ?? '1');
        $this->is3DSec = (string) ($data['is3DSec'] ?? '0');
        $this->urlMerchantResponse = (string) ($data['urlMerchantResponse'] ?? '');
    }

    /**
     * @param array<string,mixed> $data
     */
    private static function required(array $data, string $key): string
    {
        $value = trim((string) ($data[$key] ?? ''));

        if ($value === '') {
            throw new InvalidArgumentException(sprintf('Missing SISP credential: %s.', $key));
        }

        return $value;
    }
}
