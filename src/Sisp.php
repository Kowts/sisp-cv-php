<?php

declare(strict_types=1);

namespace Kowts\Sisp;

use Kowts\Sisp\Application\Action\BuildPaymentRequest;
use Kowts\Sisp\Application\Builder\PaymentBuilder;
use Kowts\Sisp\Infrastructure\Http\AutoSubmitForm;
use Kowts\Sisp\Infrastructure\Security\Fingerprint;
use Kowts\Sisp\Domain\ValueObject\CallbackPayload;
use Kowts\Sisp\Domain\ValueObject\PaymentRequest;
use Kowts\Sisp\Domain\ValueObject\SispCredentials;
use InvalidArgumentException;

final class Sisp
{
    private SispCredentials $credentials;
    private string $transactionCode;

    public function __construct(SispCredentials $credentials, string $transactionCode = '1')
    {
        $this->credentials = $credentials;
        $this->transactionCode = $transactionCode;
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

    public function validateCallback(CallbackPayload $payload): bool
    {
        return Fingerprint::validateCallback(Fingerprint::computeToken($this->credentials->posAutCode), $payload);
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
}
