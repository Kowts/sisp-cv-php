<?php

declare(strict_types=1);

namespace Kowts\Sisp\Domain\ValueObject;

final class CallbackPayload
{
    public string $merchantRef;
    public string $merchantSession;
    public string $timeStamp;
    /** @var float|int|string */
    public $amount;
    public string $currency;
    public string $transactionCode;
    /** @var float|int|string */
    public $transactionID;
    public string $messageType;
    public string $merchantResponse;
    public string $responseCode;
    public string $fingerprint;
    public string $posID;
    public string $messageID;
    public string $pan;
    public string $clearingPeriod;
    public string $reference;
    public string $entityCode;
    public string $clientReceipt;
    public string $additionalErrorMessage;
    public string $merchantRespCp;
    public string $reloadCode;
    public bool $currencyProvided;
    public bool $transactionCodeProvided;
    public bool $posIDProvided;

    /**
     * @param array<string,mixed> $data
     */
    public function __construct(array $data)
    {
        $this->merchantRef = self::text($data['merchantRef'] ?? '');
        $this->merchantSession = self::text($data['merchantSession'] ?? '');
        $this->timeStamp = self::text($data['timeStamp'] ?? '');
        $this->amount = $data['amount'] ?? 0;
        $this->currency = self::text($data['currency'] ?? '');
        $this->transactionCode = self::text($data['transactionCode'] ?? '');
        $this->transactionID = $data['transactionID'] ?? '';
        $this->messageType = self::text($data['messageType'] ?? '');
        $this->merchantResponse = self::text($data['merchantResponse'] ?? '');
        $this->responseCode = self::text($data['responseCode'] ?? '');
        $this->fingerprint = self::text($data['fingerprint'] ?? '');
        $this->posID = self::text($data['posID'] ?? '');
        $this->messageID = self::text($data['messageID'] ?? '');
        $this->pan = self::text($data['pan'] ?? '');
        $this->clearingPeriod = self::text($data['clearingPeriod'] ?? '');
        $this->reference = self::text($data['reference'] ?? '');
        $this->entityCode = self::text($data['entityCode'] ?? '');
        $this->clientReceipt = self::text($data['clientReceipt'] ?? '');
        $this->additionalErrorMessage = self::text($data['additionalErrorMessage'] ?? '');
        $this->merchantRespCp = self::text($data['merchantRespCp'] ?? $this->clearingPeriod);
        $this->reloadCode = self::text($data['reloadCode'] ?? '');
        $this->currencyProvided = (bool) ($data['currencyProvided'] ?? false);
        $this->transactionCodeProvided = (bool) ($data['transactionCodeProvided'] ?? false);
        $this->posIDProvided = (bool) ($data['posIDProvided'] ?? false);
    }

    /**
     * @param array<string,mixed> $post
     */
    public static function fromPost(array $post): self
    {
        return new self([
            'merchantRef' => self::text($post['merchantRespMerchantRef'] ?? ''),
            'merchantSession' => self::text($post['merchantRespMerchantSession'] ?? ''),
            'timeStamp' => self::text($post['merchantRespTimeStamp'] ?? ''),
            'amount' => self::scalar($post['merchantRespPurchaseAmount'] ?? null) ?? 0,
            'currency' => self::text($post['currency'] ?? ''),
            'transactionCode' => self::text($post['transactionCode'] ?? ''),
            'transactionID' => self::scalar($post['merchantRespTid'] ?? null) ?? '',
            'messageType' => self::text($post['messageType'] ?? ''),
            'merchantResponse' => self::text($post['merchantResp'] ?? ''),
            'responseCode' => self::text($post['merchantRespCP'] ?? ''),
            'fingerprint' => self::text($post['resultFingerPrint'] ?? ''),
            'posID' => self::text($post['posID'] ?? ''),
            'messageID' => self::text($post['merchantRespMessageID'] ?? ''),
            'pan' => self::text($post['merchantRespPan'] ?? ''),
            'clearingPeriod' => self::text($post['merchantRespCP'] ?? ''),
            'reference' => self::text($post['merchantRespReferenceNumber'] ?? ''),
            'entityCode' => self::text($post['merchantRespEntityCode'] ?? ''),
            'clientReceipt' => self::text($post['merchantRespClientReceipt'] ?? ''),
            'additionalErrorMessage' => self::text($post['merchantRespAdditionalErrorMessage'] ?? ''),
            'merchantRespCp' => self::text($post['merchantRespCP'] ?? ''),
            'reloadCode' => self::text($post['reloadCode'] ?? ''),
            'currencyProvided' => array_key_exists('currency', $post),
            'transactionCodeProvided' => array_key_exists('transactionCode', $post),
            'posIDProvided' => array_key_exists('posID', $post),
        ]);
    }

    /**
     * @return array<string,float|int|string>
     */
    public function toFormFields(): array
    {
        return [
            'merchantRespMerchantRef' => $this->merchantRef,
            'merchantRespMerchantSession' => $this->merchantSession,
            'merchantRespTimeStamp' => $this->timeStamp,
            'merchantRespPurchaseAmount' => $this->amount,
            'currency' => $this->currency,
            'transactionCode' => $this->transactionCode,
            'merchantRespTid' => $this->transactionID,
            'messageType' => $this->messageType,
            'merchantResp' => $this->merchantResponse,
            'merchantRespCP' => $this->merchantRespCp,
            'resultFingerPrint' => $this->fingerprint,
            'posID' => $this->posID,
            'merchantRespMessageID' => $this->messageID,
            'merchantRespPan' => $this->pan,
            'merchantRespReferenceNumber' => $this->reference,
            'merchantRespEntityCode' => $this->entityCode,
            'merchantRespClientReceipt' => $this->clientReceipt,
            'merchantRespAdditionalErrorMessage' => $this->additionalErrorMessage,
            'reloadCode' => $this->reloadCode,
        ];
    }

    /**
     * @param mixed $value
     */
    private static function text($value): string
    {
        return is_string($value) || is_int($value) || is_float($value) ? (string) $value : '';
    }

    /**
     * @param mixed $value
     * @return float|int|string|null
     */
    private static function scalar($value)
    {
        return is_string($value) || is_int($value) || is_float($value) ? $value : null;
    }
}
