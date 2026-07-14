# Segurança

## Fingerprints

O token e `base64(sha512(posAutCode))`. O pedido de pagamento concatena token,
timestamp, amount em milésimos, merchant reference, merchant session, POS ID,
currency e transaction code.

Callbacks usam uma ordem fixa de campos e devem ser comparados em tempo
constante. O pacote usa digest HMAC antes de `hash_equals` para normalizar
tamanhos.

## Dados sensíveis

Nunca exponha:

- `posAutCode`;
- tokens ou secrets;
- PAN completo, CVV, PIN ou dados reais de cartão;
- recibos reais sem anonimizar;
- dados pessoais em issues ou logs.

## Produção

Use HTTPS, armazene credenciais fora do repositório, fixe versoes do pacote e
monitorize callbacks inválidos, transações pendentes e tentativas duplicadas.
