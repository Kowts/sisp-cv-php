# Seguranca

## Fingerprints

O token e `base64(sha512(posAutCode))`. O pedido de pagamento concatena token,
timestamp, amount em milesimos, merchant reference, merchant session, POS ID,
currency e transaction code.

Callbacks usam uma ordem fixa de campos e devem ser comparados em tempo
constante. O pacote usa digest HMAC antes de `hash_equals` para normalizar
tamanhos.

## Dados sensiveis

Nunca exponha:

- `posAutCode`;
- tokens ou secrets;
- PAN completo, CVV, PIN ou dados reais de cartao;
- recibos reais sem anonimizar;
- dados pessoais em issues ou logs.

## Producao

Use HTTPS, armazene credenciais fora do repositorio, fixe versoes do pacote e
monitorize callbacks invalidos, transacoes pendentes e tentativas duplicadas.
