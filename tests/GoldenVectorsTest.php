<?php

declare(strict_types=1);

use Akira\SispPhp\Security\Fingerprint;
use Akira\SispPhp\Support\SispAmount;
use Akira\SispPhp\ValueObjects\CallbackPayload;

require __DIR__.'/../vendor/autoload.php';

$tests = 0;

function assertSameValue($expected, $actual, string $message): void
{
    global $tests;
    $tests++;

    if ($expected !== $actual) {
        fwrite(STDERR, "FAIL: {$message}\nExpected: ".var_export($expected, true)."\nActual:   ".var_export($actual, true)."\n");
        exit(1);
    }
}

assertSameValue(
    'YWA0ctSRArxk5T86DOJbbHjSitPq8fM4Uh8AmZuNMBIQlIk5iB2yP+d+KpDsJoqZ/z7kcNFNa6pNS0T511FvxQ==',
    Fingerprint::computeToken('TEST_POS_AUT_CODE'),
    'token fingerprint'
);

assertSameValue(8030, SispAmount::toThousandths('8.03'), 'amount string to thousandths');
assertSameValue(8030, SispAmount::toThousandths(8.03), 'amount float to thousandths');
assertSameValue(803, SispAmount::toCents('8.0295'), 'amount to cents');
assertSameValue(-7001, SispAmount::toThousandths('-7.0005'), 'negative amount rounds away');

$token = Fingerprint::computeToken('secret');

assertSameValue(
    '36ydllpRTMPAD5DPmS7ykc5DI5EmmB0Xc6t8THDgmXQMb0VL/L5cJ/gFAvpGVlXGl/Xzn+1BpCdZ6uklAyLXNw==',
    Fingerprint::payment($token, [
        'timeStamp' => '2026-06-12 10:00:00',
        'amount' => '1500',
        'merchantRef' => 'R20260612100000',
        'merchantSession' => 'S20260612100000',
        'posID' => '90051',
        'currency' => '132',
        'transactionCode' => '1',
    ]),
    'payment fingerprint'
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

assertSameValue(
    '66kXBYKYoeerFDCXaKJso0Ck1UQmt6hSce75FjScUxwFTD1TGbsqx57Zr+9QeFNJUKyHYmfPupneiCEXZVoBCg==',
    Fingerprint::callback(Fingerprint::computeToken('TEST_POS_AUT_CODE'), $callback),
    'callback fingerprint'
);

assertSameValue(
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
    ]),
    'refund fingerprint'
);

echo "OK: {$tests} assertions\n";
