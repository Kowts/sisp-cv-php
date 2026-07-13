# Yii2

Configure o componente:

```php
'components' => [
    'sisp' => [
        'class' => Kowts\Sisp\Bridge\Yii2\SispComponent::class,
        'config' => [
            'posId' => '90051',
            'posAutCode' => 'secret',
            'url' => 'https://gateway.example/pay',
            'urlMerchantResponse' => 'https://app.example/sisp/callback',
        ],
    ],
],
```

Use `Yii::$app->sisp->getClient()`.
