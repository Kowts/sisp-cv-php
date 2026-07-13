<?php

declare(strict_types=1);

namespace Kowts\Sisp\Application\Builder;

use Kowts\Sisp\Application\Action\BuildPaymentRequest;
use Kowts\Sisp\Domain\Amount\SispAmount;
use Kowts\Sisp\Domain\ValueObject\PaymentRequest;
use InvalidArgumentException;

final class PaymentBuilder
{
    private BuildPaymentRequest $buildPaymentRequest;

    /**
     * @var array<string,mixed>
     */
    private array $data = [];

    public function __construct(BuildPaymentRequest $buildPaymentRequest)
    {
        $this->buildPaymentRequest = $buildPaymentRequest;
    }

    /**
     * @param float|int|string $amount
     */
    public function amount($amount): self
    {
        $this->data['amount'] = $amount;

        return $this;
    }

    public function merchantRef(string $merchantRef): self
    {
        $this->data['merchantRef'] = $merchantRef;

        return $this;
    }

    public function merchantSession(string $merchantSession): self
    {
        $this->data['merchantSession'] = $merchantSession;

        return $this;
    }

    public function timeStamp(string $timeStamp): self
    {
        $this->data['timeStamp'] = $timeStamp;

        return $this;
    }

    public function currency(string $currency): self
    {
        $this->data['currency'] = $currency;

        return $this;
    }

    public function transactionCode(string $transactionCode): self
    {
        $this->data['transactionCode'] = $transactionCode;

        return $this;
    }

    public function token(string $token): self
    {
        $this->data['token'] = $token;

        return $this;
    }

    public function entityCode(string $entityCode): self
    {
        $this->data['entityCode'] = $entityCode;

        return $this;
    }

    public function referenceNumber(string $referenceNumber): self
    {
        $this->data['referenceNumber'] = $referenceNumber;

        return $this;
    }

    public function locale(string $locale): self
    {
        $this->data['locale'] = $locale;

        return $this;
    }

    public function customerEmail(string $email): self
    {
        $this->data['customerEmail'] = $email;

        return $this;
    }

    public function customerCountry(string $country): self
    {
        $this->data['customerCountry'] = $country;

        return $this;
    }

    public function customerCity(string $city): self
    {
        $this->data['customerCity'] = $city;

        return $this;
    }

    public function customerAddress(string $address): self
    {
        $this->data['customerAddress'] = $address;

        return $this;
    }

    public function customerPostalCode(string $postalCode): self
    {
        $this->data['customerPostalCode'] = $postalCode;

        return $this;
    }

    public function customerPhone(string $phone): self
    {
        $this->data['customerPhone'] = $phone;

        return $this;
    }

    public function build(): PaymentRequest
    {
        if (! array_key_exists('amount', $this->data) || SispAmount::toThousandths($this->data['amount']) <= 0) {
            throw new InvalidArgumentException('A payment amount greater than zero is required.');
        }

        return $this->buildPaymentRequest->handle($this->data);
    }
}
