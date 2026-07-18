<?php

declare(strict_types=1);

return [
    'posId' => $_ENV['SISP_POS_ID'] ?? '',
    'posAutCode' => $_ENV['SISP_POS_AUT_CODE'] ?? '',
    'url' => $_ENV['SISP_URL'] ?? '',
    'urlMerchantResponse' => $_ENV['SISP_CALLBACK_URL'] ?? '',
    'currency' => $_ENV['SISP_CURRENCY'] ?? '132',
    'languageMessages' => $_ENV['SISP_LANGUAGE_MESSAGES'] ?? 'EN',
    'fingerprintVersion' => $_ENV['SISP_FINGERPRINT_VERSION'] ?? '1',
    'is3DSec' => $_ENV['SISP_3DS'] ?? '0',
    'transactionCode' => $_ENV['SISP_TRANSACTION_CODE'] ?? '1',
    'autoMigrate' => filter_var($_ENV['SISP_AUTO_MIGRATE'] ?? false, FILTER_VALIDATE_BOOL),
];
