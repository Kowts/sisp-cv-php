# Pagamentos

O pedido de pagamento gera os campos esperados pelo gateway:

- `posID`;
- `merchantRef`;
- `merchantSession`;
- `amount`;
- `currency`;
- `timeStamp`;
- `transactionCode`;
- `fingerprint`;
- campos opcionais de 3DS.

## Formulario auto-submit

`renderPaymentForm($request)` devolve HTML que submete automaticamente para o
endpoint configurado em `url`. O URL de destino inclui `FingerPrint`,
`TimeStamp` e `FingerPrintVersion` na query string.

## SPA / frontend separado

Para SPAs, o backend deve devolver os campos de `$request->toFormFields()` e o
frontend deve construir um formulário full-page. Não envie `posAutCode` nem
qualquer segredo para o browser.

## 3DS

Quando `is3DSec` e `1`, informe dados de cliente suficientes para o payload
3DS: email, país, cidade, endereço, código postal e telefone opcional.
