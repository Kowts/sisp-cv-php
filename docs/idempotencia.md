# Idempotência

Pagamentos não devem criar transações duplicadas quando o utilizador recarrega
a página ou repete o checkout.

O pacote ja inclui `PaymentIntentStore` como porta para reservar uma chave de
checkout e ligar essa chave a uma transação. A aplicação pode usar essa porta
para guardar `checkout_intent_id` ou outro identificador unico por compra.

## Recomendação

- gere uma chave por checkout;
- reserve antes de construir o pedido SISP;
- ligue a chave ao `transaction_id` depois de persistir o pedido;
- em repetição da mesma chave, reutilize a transação existente.

Idempotência não substitui fingerprints. Fingerprints provam integridade da
mensagem SISP; idempotência evita duplicação local.
