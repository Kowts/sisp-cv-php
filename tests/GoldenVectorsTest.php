<?php

declare(strict_types=1);

namespace Kowts\Sisp\Tests;

use Kowts\Sisp\Domain\Amount\SispAmount;
use Kowts\Sisp\Domain\ValueObject\CallbackPayload;
use Kowts\Sisp\Infrastructure\Security\Fingerprint;
use PHPUnit\Framework\TestCase;

final class GoldenVectorsTest extends TestCase
{
    public function testTokenPaymentCallbackAndRefundFingerprints(): void
    {
        self::assertSame(
            'YWA0ctSRArxk5T86DOJbbHjSitPq8fM4Uh8AmZuNMBIQlIk5iB2yP+d+KpDsJoqZ/z7kcNFNa6pNS0T511FvxQ==',
            Fingerprint::computeToken('TEST_POS_AUT_CODE')
        );

        $token = Fingerprint::computeToken('secret');

        self::assertSame(
            '36ydllpRTMPAD5DPmS7ykc5DI5EmmB0Xc6t8THDgmXQMb0VL/L5cJ/gFAvpGVlXGl/Xzn+1BpCdZ6uklAyLXNw==',
            Fingerprint::payment($token, [
                'timeStamp' => '2026-06-12 10:00:00',
                'amount' => '1500',
                'merchantRef' => 'R20260612100000',
                'merchantSession' => 'S20260612100000',
                'posID' => '90051',
                'currency' => '132',
                'transactionCode' => '1',
            ])
        );

        $callback = CallbackPayload::fromPost([
            'messageType' => '8',
            'merchantRespCP' => '01',
            'merchantRespTid' => 'FAKE12345678',
            'merchantRespMerchantRef' => 'R20260612100000',
            'merchantRespMerchantSession' => 'S20260612100000',
            'merchantRespPurchaseAmount' => '1500',
            'merchantRespMessageID' => 'MSG-ABCDEFGH',
            'merchantRespPan' => '****-****-****-1234',
            'merchantResp' => '00',
            'merchantRespTimeStamp' => '2026-06-12 10:00:05',
            'merchantRespReferenceNumber' => 'REF123456789',
            'merchantRespEntityCode' => '10010',
            'merchantRespClientReceipt' => 'RECEIPT-XYZ',
            'merchantRespAdditionalErrorMessage' => '',
            'reloadCode' => '',
            'posID' => '90051',
            'currency' => '132',
            'transactionCode' => '1',
        ]);

        self::assertSame(
            '66kXBYKYoeerFDCXaKJso0Ck1UQmt6hSce75FjScUxwFTD1TGbsqx57Zr+9QeFNJUKyHYmfPupneiCEXZVoBCg==',
            Fingerprint::callback(Fingerprint::computeToken('TEST_POS_AUT_CODE'), $callback)
        );

        self::assertSame(
            'okZCRnIqdfyM3EsIsrzTmCNsNLoY9tzGM7/bIpIpkL+tW2fsy+hDHtz8lGHrU9qbJ3iw5aC6zwxDn7KW+bvzZg==',
            Fingerprint::refund(Fingerprint::computeToken('TEST_POS_AUT_CODE'), [
                'timeStamp' => '2026-06-12 10:00:00',
                'amount' => '1500',
                'merchantRef' => 'R20260612100000',
                'merchantSession' => 'S20260612100000',
                'posID' => '90051',
                'currency' => '132',
                'transactionCode' => '4',
                'clearingPeriod' => '42',
                'transactionID' => '123',
            ])
        );
    }

    public function testAmountConversion(): void
    {
        self::assertSame(8030, SispAmount::toThousandths('8.03'));
        self::assertSame(8030, SispAmount::toThousandths(8.03));
        self::assertSame(803, SispAmount::toCents('8.0295'));
        self::assertSame(-7001, SispAmount::toThousandths('-7.0005'));
    }

    public function testCallbackSafeLogContextRedactsSensitiveValues(): void
    {
        $payload = CallbackPayload::fromPost([
            'merchantRespMerchantRef' => 'R1',
            'merchantRespMerchantSession' => 'S1',
            'merchantRespTid' => 'T1',
            'merchantRespPan' => '****-****-****-1234',
            'resultFingerPrint' => 'sensitive-fingerprint',
            'merchantRespClientReceipt' => 'sensitive-receipt',
        ]);

        self::assertSame('***redacted***', $payload->toSafeLogContext()['pan']);
        self::assertArrayNotHasKey('fingerprint', $payload->toSafeLogContext());
        self::assertArrayNotHasKey('clientReceipt', $payload->toSafeLogContext());
    }
}
