# Laravel

O provider é descoberto pelo Composer. Publique a configuração apenas quando
precisar de a adaptar; em seguida, use variáveis de ambiente para credenciais.

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

O endpoint de callback deve chamar `CallbackPayload::fromPost($request->all())`,
validar o fingerprint e delegar em `handleCallback()`. Devolva uma resposta HTTP
curta; a página de resultado do utilizador deve consultar o estado local.
