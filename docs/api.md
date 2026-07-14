# API

## Criar cliente

```php
use Kowts\Sisp\Config\SispConfig;
use Kowts\Sisp\SispFactory;

$sisp = SispFactory::create(SispConfig::fromArray([
    'posId' => '90051',
    'posAutCode' => getenv('SISP_POS_AUT_CODE'),
    'url' => getenv('SISP_URL'),
    'urlMerchantResponse' => 'https://app.example/sisp/callback',
]));
```

## Criar pagamento sem persistencia

```php
$request = $sisp->payment()
    ->amount('1500')
    ->merchantRef('R20260713120000')
    ->merchantSession('S20260713120000')
    ->build();
```

## Criar pagamento com persistencia

```php
$request = $sisp->createPayment([
    'amount' => '1500',
    'merchantRef' => 'R20260713120000',
    'merchantSession' => 'S20260713120000',
]);
```

## Callback

```php
use Kowts\Sisp\Domain\ValueObject\CallbackPayload;

$payload = CallbackPayload::fromPost($_POST);

if (!$sisp->validateCallback($payload)) {
    http_response_code(400);
    exit('Invalid callback');
}

$transaction = $sisp->handleCallback($payload);
```

Consulte tambem a [referencia automatica](api-reference.md).
