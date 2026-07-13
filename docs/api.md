# API

## Criar cliente

```php
use Kowts\Sisp\Config\SispConfig;
use Kowts\Sisp\SispFactory;

$sisp = SispFactory::create(SispConfig::fromArray([
    'posId' => '90051',
    'posAutCode' => 'secret',
    'url' => 'https://gateway.example/pay',
    'urlMerchantResponse' => 'https://app.example/sisp/callback',
]));
```

## Criar pagamento

```php
$request = $sisp->payment()
    ->amount('1500')
    ->merchantRef('R20260713120000')
    ->merchantSession('S20260713120000')
    ->build();
```

Use `createPayment([...])` quando existir storage configurado e quiser persistir a transacao.
