# Idempotência

Idempotência impede que uma dupla submissão do checkout crie duas transações
SISP para a mesma compra. Não substitui o fingerprint: o fingerprint protege a
integridade do pedido; a idempotência protege a operação local.

## Estratégia recomendada

1. Crie uma chave aleatória por tentativa de checkout, associada à encomenda.
2. Reserve a chave antes de construir o pedido SISP.
3. Crie e persista o pedido uma única vez.
4. Ligue a chave ao `transaction_id` persistido.
5. Quando a chave se repetir, devolva a transação existente em vez de criar outra.

`PaymentIntentStore` define as operações `reserve`, `link` e `find`. A
aplicação pode usar `PdoPaymentIntentStore` ou implementar o contrato no seu
próprio armazenamento.

## Limites

Use uma chave diferente quando a encomenda muda de montante, moeda ou conteúdo.
Defina uma política de expiração coerente com o checkout e registe tentativas
rejeitadas. Uma restrição única em base de dados é a última linha de defesa; não
depende apenas de verificações em memória ou cookies do browser.
