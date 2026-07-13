# Pagamentos

O pedido de pagamento gera:

- `merchantRef`
- `merchantSession`
- `timeStamp`
- fingerprint SHA-512 em Base64
- campos HTML para submissao ao gateway

Para ambientes web tradicionais, envie `renderPaymentForm($request)` como resposta HTTP. Para SPA, use `$request->toFormFields()` e submeta um formulario full-page no browser.
