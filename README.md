# SISP PHP

Cliente PHP core para pagamentos SISP/Vinti4, criado para funcionar em PHP puro e com bridges opcionais para Laravel, Symfony e Yii2.

Este primeiro corte evita dependencias de framework. Ele cobre o contrato critico do gateway: token, fingerprints, montantes em milesimos, payload 3DS, validacao de callback e formulario auto-submit.

## Instalar localmente

```bash
composer dump-autoload
```

## Exemplo rapido

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use Kowts\Sisp\Sisp;
use Kowts\Sisp\ValueObjects\SispCredentials;

$sisp = new Sisp(new SispCredentials([
    'posId' => '90051',
    'posAutCode' => 'secret',
    'url' => 'https://pagali.vinti4.cv/Vinti4-Pagamentos/PaymentGateway',
    'urlMerchantResponse' => 'https://example.com/sisp/callback',
]));

$request = $sisp->payment()
    ->amount('1500')
    ->merchantRef('R20260713120000')
    ->merchantSession('S20260713120000')
    ->build();

echo $sisp->renderPaymentForm($request);
```

## Testes

```bash
composer test
```

Os testes usam vetores de paridade para confirmar tokens e fingerprints.
