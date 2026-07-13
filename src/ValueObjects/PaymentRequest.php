<?php

declare(strict_types=1);

namespace Kowts\Sisp\ValueObjects;

final class PaymentRequest
{
    public string $posID;
    public string $merchantRef;
    public string $merchantSession;
    /** @var float|int|string */
    public $amount;
    public string $currency;
    public string $is3DSec;
    public string $urlMerchantResponse;
    public string $languageMessages;
    public string $timeStamp;
    public string $fingerprintversion;
    public string $transactionCode;
    public string $fingerprint;
    public string $token;
    public string $entityCode;
    public string $referenceNumber;
    public string $locale;
    public string $purchaseRequest;

    /**
     * @param array<string,mixed> $data
     */
    public function __construct(array $data)
    {
        $this->posID = (string) ($data['posID'] ?? '');
        $this->merchantRef = (string) ($data['merchantRef'] ?? '');
        $this->merchantSession = (string) ($data['merchantSession'] ?? '');
        $this->amount = $data['amount'] ?? 0;
        $this->currency = (string) ($data['currency'] ?? '');
        $this->is3DSec = (string) ($data['is3DSec'] ?? '0');
        $this->urlMerchantResponse = (string) ($data['urlMerchantResponse'] ?? '');
        $this->languageMessages = (string) ($data['languageMessages'] ?? 'EN');
        $this->timeStamp = (string) ($data['timeStamp'] ?? '');
        $this->fingerprintversion = (string) ($data['fingerprintversion'] ?? '1');
        $this->transactionCode = (string) ($data['transactionCode'] ?? '1');
        $this->fingerprint = (string) ($data['fingerprint'] ?? '');
        $this->token = (string) ($data['token'] ?? '');
        $this->entityCode = (string) ($data['entityCode'] ?? '');
        $this->referenceNumber = (string) ($data['referenceNumber'] ?? '');
        $this->locale = (string) ($data['locale'] ?? 'pt_PT');
        $this->purchaseRequest = (string) ($data['purchaseRequest'] ?? '');
    }

    /**
     * @return array<string,float|int|string>
     */
    public function toFormFields(): array
    {
        $fields = [
            'posID' => $this->posID,
            'merchantRef' => $this->merchantRef,
            'merchantSession' => $this->merchantSession,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'is3DSec' => $this->is3DSec,
            'urlMerchantResponse' => $this->urlMerchantResponse,
            'languageMessages' => $this->languageMessages,
            'timeStamp' => $this->timeStamp,
            'fingerprintversion' => $this->fingerprintversion,
            'transactionCode' => $this->transactionCode,
            'fingerprint' => $this->fingerprint,
            'token' => $this->token,
            'entityCode' => $this->entityCode,
            'referenceNumber' => $this->referenceNumber,
            'locale' => $this->locale,
        ];

        if ($this->purchaseRequest !== '') {
            $fields['purchaseRequest'] = $this->purchaseRequest;
        }

        return $fields;
    }
}
