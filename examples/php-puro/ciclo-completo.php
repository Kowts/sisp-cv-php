<?php

declare(strict_types=1);

require __DIR__.'/../../vendor/autoload.php';

use Kowts\Sisp\Config\SispConfig;
use Kowts\Sisp\SispFactory;

$pdo = new PDO('sqlite:'.__DIR__.'/sisp.sqlite');

$sisp = SispFactory::create(SispConfig::fromArray([
    'posId' => '90051',
    'posAutCode' => 'secret',
    'url' => 'https://gateway.example/pay',
    'urlMerchantResponse' => 'https://app.example/sisp/callback',
    'pdo' => $pdo,
]));

$request = $sisp->createPayment([
    'amount' => '1500',
    'merchantRef' => 'R'.date('YmdHis'),
    'merchantSession' => 'S'.date('YmdHis'),
]);

echo $sisp->renderPaymentForm($request);
