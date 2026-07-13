<?php

declare(strict_types=1);

namespace Kowts\Sisp\Application\Action;

use Kowts\Sisp\Infrastructure\Security\Fingerprint;
use Kowts\Sisp\Support\Generators;
use Kowts\Sisp\Domain\ValueObject\PaymentRequest;
use Kowts\Sisp\Domain\ValueObject\SispCredentials;
use InvalidArgumentException;

final class BuildPaymentRequest
{
    private SispCredentials $credentials;
    private string $transactionCode;

    public function __construct(SispCredentials $credentials, string $transactionCode = '1')
    {
        $this->credentials = $credentials;
        $this->transactionCode = $transactionCode;
    }

    /**
     * @param array<string,mixed> $data
     */
    public function handle(array $data): PaymentRequest
    {
        $request = [
            'posID' => $this->credentials->posId,
            'merchantRef' => (string) ($data['merchantRef'] ?? Generators::merchantReference()),
            'merchantSession' => (string) ($data['merchantSession'] ?? Generators::merchantSession()),
            'amount' => $data['amount'] ?? 0,
            'currency' => (string) ($data['currency'] ?? $this->credentials->currency),
            'is3DSec' => $this->credentials->is3DSec,
            'urlMerchantResponse' => (string) ($data['urlMerchantResponse'] ?? $this->credentials->urlMerchantResponse),
            'languageMessages' => $this->credentials->languageMessages,
            'timeStamp' => (string) ($data['timeStamp'] ?? Generators::timeStamp()),
            'fingerprintversion' => $this->credentials->fingerprintVersion,
            'transactionCode' => (string) ($data['transactionCode'] ?? $this->transactionCode),
            'token' => (string) ($data['token'] ?? ''),
            'entityCode' => (string) ($data['entityCode'] ?? ''),
            'referenceNumber' => (string) ($data['referenceNumber'] ?? ''),
            'locale' => (string) ($data['locale'] ?? 'pt_PT'),
            'purchaseRequest' => $this->buildPurchaseRequestIfNeeded($data),
        ];

        $request['fingerprint'] = Fingerprint::payment(Fingerprint::computeToken($this->credentials->posAutCode), $request);

        return new PaymentRequest($request);
    }

    /**
     * @param array<string,mixed> $data
     */
    private function buildPurchaseRequestIfNeeded(array $data): string
    {
        if ($this->credentials->is3DSec !== '1') {
            return '';
        }

        $required = [
            'customerEmail',
            'customerCountry',
            'customerCity',
            'customerAddress',
            'customerPostalCode',
        ];

        $missing = [];

        foreach ($required as $field) {
            if (! array_key_exists($field, $data) || trim((string) $data[$field]) === '') {
                $missing[] = $field;
            }
        }

        if ($missing !== []) {
            throw new InvalidArgumentException('Missing 3DS customer fields: '.implode(', ', $missing).'.');
        }

        return BuildPurchaseRequest::handle([
            'email' => (string) $data['customerEmail'],
            'country' => (string) $data['customerCountry'],
            'city' => (string) $data['customerCity'],
            'address' => (string) $data['customerAddress'],
            'postalCode' => (string) $data['customerPostalCode'],
            'phone' => isset($data['customerPhone']) ? (string) $data['customerPhone'] : null,
        ]);
    }
}
