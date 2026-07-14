# Symfony

Registe `Kowts\Sisp\Bridge\Symfony\SispBundle` e configure o servico com os
parametros SISP da aplicacao.

O servico principal fica em `kowts_sisp.client` e tambem pode ser injectado por
tipo como `Kowts\Sisp\Sisp`.

```php
use Kowts\Sisp\Sisp;
use Symfony\Component\HttpFoundation\Response;

final class PaymentController
{
    public function __invoke(Sisp $sisp): Response
    {
        $request = $sisp->createPayment([
            'amount' => '1500',
            'merchantRef' => 'R'.date('YmdHis'),
            'merchantSession' => 'S'.date('YmdHis'),
        ]);

        return new Response($sisp->renderPaymentForm($request));
    }
}
```
