# Laravel

Publique a configuração:

```bash
php artisan vendor:publish --tag=sisp-config
```

Configure o `.env`:

```dotenv
SISP_POS_ID=90051
SISP_POS_AUT_CODE=secret
SISP_URL=https://gateway.example/pay
SISP_CALLBACK_URL=https://app.example/sisp/callback
```

Use no controller:

```php
use Kowts\Sisp\Sisp;

public function pay(Sisp $sisp)
{
    $request = $sisp->createPayment([
        'amount' => '1500',
        'merchantRef' => 'R'.date('YmdHis'),
        'merchantSession' => 'S'.date('YmdHis'),
    ]);

    return response($sisp->renderPaymentForm($request));
}
```
