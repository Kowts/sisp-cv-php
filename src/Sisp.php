<?php

declare(strict_types=1);

namespace Kowts\Sisp;

use Kowts\Sisp\Application\Action\BuildPaymentRequest;
use Kowts\Sisp\Application\Builder\PaymentBuilder;
use Kowts\Sisp\Contract\TransactionStore;
use Kowts\Sisp\Domain\TransactionStatus;
use Kowts\Sisp\Infrastructure\Http\AutoSubmitForm;
use Kowts\Sisp\Infrastructure\Security\Fingerprint;
use Kowts\Sisp\Domain\ValueObject\CallbackPayload;
use Kowts\Sisp\Domain\ValueObject\PaymentRequest;
use Kowts\Sisp\Domain\ValueObject\SispCredentials;
use Kowts\Sisp\Domain\ValueObject\TransactionRecord;
use InvalidArgumentException;

final class Sisp
{
    private SispCredentials $credentials;
    private string $transactionCode;
    private ?TransactionStore $transactionStore;

    public function __construct(SispCredentials $credentials, string $transactionCode = '1', ?TransactionStore $transactionStore = null)
    {
        $this->credentials = $credentials;
        $this->transactionCode = $transactionCode;
        $this->transactionStore = $transactionStore;
    }

    public function payment(): PaymentBuilder
    {
        return new PaymentBuilder(new BuildPaymentRequest($this->credentials, $this->transactionCode));
    }

    /**
     * @param array<string,mixed> $data
     */
    public function buildRequestPayload(array $data): PaymentRequest
    {
        return (new BuildPaymentRequest($this->credentials, $this->transactionCode))->handle($data);
    }

    /**
     * @param array<string,mixed> $data
     */
    public function createPayment(array $data): PaymentRequest
    {
        $request = $this->buildRequestPayload($data);

        if ($this->transactionStore !== null) {
            $this->transactionStore->storePaymentRequest($request);
        }

        return $request;
    }

    public function validateCallback(CallbackPayload $payload): bool
    {
        return Fingerprint::validateCallback(Fingerprint::computeToken($this->credentials->posAutCode), $payload);
    }

    public function handleCallback(CallbackPayload $payload): ?TransactionRecord
    {
        if (! $this->validateCallback($payload) || $this->transactionStore === null) {
            return null;
        }

        $transaction = $this->transactionStore->findByMerchantIdentifiers($payload->merchantRef, $payload->merchantSession);

        if ($transaction === null) {
            return null;
        }

        return $this->transactionStore->applyCallback($transaction, $payload, $this->statusFromCallback($payload));
    }

    public function gatewayFormAction(PaymentRequest $request): string
    {
        $endpoint = trim($this->credentials->url);

        if ($endpoint === '') {
            throw new InvalidArgumentException('SISP payment URL is required to render the payment form.');
        }

        $query = http_build_query([
            'FingerPrint' => $request->fingerprint,
            'TimeStamp' => $request->timeStamp,
            'FingerPrintVersion' => $request->fingerprintversion,
        ]);

        return $endpoint.(strpos($endpoint, '?') === false ? '?' : '&').$query;
    }

    public function renderPaymentForm(PaymentRequest $request, string $title = 'Redirecting to SISP'): string
    {
        return AutoSubmitForm::render($this->gatewayFormAction($request), $request->toFormFields(), $title);
    }

    private function statusFromCallback(CallbackPayload $payload): string
    {
        if (in_array($payload->messageType, ['8', 'P', 'M', 'A', 'B', 'C', '10'], true)) {
            return TransactionStatus::COMPLETED;
        }

        if ($payload->messageType === '6' || ($payload->merchantResponse !== '' && $payload->merchantResponse !== '00')) {
            return TransactionStatus::FAILED;
        }

        return TransactionStatus::PENDING;
    }
}
