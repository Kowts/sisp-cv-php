# Yii2

Configure o componente:

```php
'components' => [
    'sisp' => [
        'class' => Kowts\Sisp\Bridge\Yii2\SispComponent::class,
        'config' => [
            'posId' => '90051',
            'posAutCode' => getenv('SISP_POS_AUT_CODE'),
            'url' => getenv('SISP_URL'),
            'urlMerchantResponse' => 'https://app.example/sisp/callback',
        ],
    ],
],
```

Use:

```php
$request = Yii::$app->sisp->createPayment([
    'amount' => '1500',
    'merchantRef' => 'R'.date('YmdHis'),
    'merchantSession' => 'S'.date('YmdHis'),
]);

return Yii::$app->response->sendContentAsFile(
    Yii::$app->sisp->renderPaymentForm($request),
    'sisp-payment.html',
    ['mimeType' => 'text/html', 'inline' => true]
);
```

Em producao, prefira construir o cliente com PDO persistente para guardar
transacoes e callbacks.
