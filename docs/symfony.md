# Symfony

Registe `Kowts\Sisp\Bridge\Symfony\SispBundle` e configure o serviço com os
parâmetros SISP da aplicação.

O serviço principal fica em `kowts_sisp.client` e também pode ser injectado por
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
