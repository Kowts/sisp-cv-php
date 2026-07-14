# API prática

Este guia cobre o fluxo público mais comum. A lista completa de símbolos e
assinaturas está na [referência automática](api-reference.md).

## Criar o cliente

`SispConfig` recebe a configuração de integração. Mantenha as credenciais em
variáveis de ambiente ou num gestor de segredos; `posAutCode` nunca deve chegar
ao browser.

```php
use Kowts\Sisp\Config\SispConfig;
use Kowts\Sisp\SispFactory;

$sisp = SispFactory::create(SispConfig::fromArray([
    'posId' => getenv('SISP_POS_ID'),
    'posAutCode' => getenv('SISP_POS_AUT_CODE'),
    'url' => getenv('SISP_URL'),
    'urlMerchantResponse' => getenv('SISP_CALLBACK_URL'),
    'currency' => '132',
    'transactionCode' => '1',
]));
```

`currency`, `transactionCode`, URLs e os campos aceites pelo adquirente devem
ser confirmados na documentação oficial do contrato SISP/Vinti4.

## Construir um pedido

Use `payment()` quando a aplicação apenas precisa do payload. Use
`createPayment()` quando existe um `TransactionStore`, normalmente criado ao
passar uma ligação PDO na configuração.

```php
$request = $sisp->createPayment([
    'amount' => '1500.00',
    'merchantRef' => 'ORDER-20260714-001',
    'merchantSession' => 'CHECKOUT-20260714-001',
    'currency' => '132',
]);

$fields = $request->toFormFields();
```

`merchantRef` e `merchantSession` identificam a transação local. Gere valores
únicos, guarde-os antes do redireccionamento e não os reutilize para outra
encomenda.

## Redireccionar o cliente

Para uma resposta HTML normal, devolva `renderPaymentForm($request)`. O método
produz um formulário que submete para o gateway em navegação de página inteira.

```php
echo $sisp->renderPaymentForm($request, 'A encaminhar para pagamento');
```

Numa SPA, devolva apenas `$request->toFormFields()` e construa um formulário
real no frontend. Não tente chamar o gateway com `fetch` e não inclua segredos
na resposta JSON.

## Tratar o callback

O callback é a fonte de confirmação para a aplicação. Valide-o antes de mudar
o estado de uma encomenda ou de emitir um recibo.

```php
use Kowts\Sisp\Domain\ValueObject\CallbackPayload;

$payload = CallbackPayload::fromPost($_POST);

if (! $sisp->validateCallback($payload)) {
    http_response_code(400);
    exit('Callback SISP inválido.');
}

$transaction = $sisp->handleCallback($payload);
```

`handleCallback()` devolve `null` quando não existe armazenamento configurado,
o fingerprint falha ou a transação não é encontrada. O guia de
[Callbacks](callbacks.md) explica a resposta HTTP, idempotência e auditoria.

## Ativar 3DS

Defina `is3DSec` como `'1'` na configuração e forneça no pedido `customerEmail`,
`customerCountry`, `customerCity`, `customerAddress` e `customerPostalCode`.
`customerPhone` é opcional. O core recusa pedidos 3DS sem esses campos.
