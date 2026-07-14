# Idempotencia

Pagamentos nao devem criar transacoes duplicadas quando o utilizador recarrega
a pagina ou repete o checkout.

O pacote ja inclui `PaymentIntentStore` como porta para reservar uma chave de
checkout e ligar essa chave a uma transacao. A aplicacao pode usar essa porta
para guardar `checkout_intent_id` ou outro identificador unico por compra.

## Recomendacao

- gere uma chave por checkout;
- reserve antes de construir o pedido SISP;
- ligue a chave ao `transaction_id` depois de persistir o pedido;
- em repeticao da mesma chave, reutilize a transacao existente.

Idempotencia nao substitui fingerprints. Fingerprints provam integridade da
mensagem SISP; idempotencia evita duplicacao local.
