# Analise dos repositorios referencia externa SISP

## O que os dois repositorios tem em comum

Ambos modelam o SISP/Vinti4 como um fluxo assinado:

1. o `posAutCode` vira `token` com `base64(sha512(posAutCode))`;
2. o pedido de pagamento concatena `token`, `timeStamp`, `amount` em milesimos, `merchantRef`, `merchantSession`, `posID`, `currency` e `transactionCode`;
3. o callback concatena 16 campos em ordem fixa e compara o fingerprint em tempo constante;
4. o reembolso usa fingerprint proprio, com `clearingPeriod` a 4 digitos e `transactionID` a 8 digitos;
5. valores monetarios nunca devem depender de multiplicacao float simples; sao convertidos decimalmente para milesimos.

## Laravel

O pacote Laravel e uma integracao completa: service provider, facade, controllers, views, migrations, models, invoices, blacklist, rate limit, retry, idempotencia e comandos de reconciliacao.

Pontos fortes para replicar:

- migrations e auditoria de transacoes;
- `sisp_transaction_attempts`, que evita callbacks antigos sobrescreverem tentativas novas;
- idempotencia com `sisp_payment_intents`;
- pipeline de callback com validacao de fingerprint antes de qualquer atualizacao critica.

## Node

O pacote Node separa melhor o dominio do framework. A arquitetura e hexagonal: core/contracts, domain, application, infrastructure e presentation. Isto e a melhor referencia para uma biblioteca PHP pura.

Pontos fortes para replicar:

- API `createSisp`/`Sisp` com builders;
- adapters opcionais para frameworks;
- storage por porta, com Knex/Prisma como implementacoes;
- testes de paridade via golden vectors.

## Decisao para este PHP

Este pacote implementa primeiro o nucleo portavel:

- `SispAmount`;
- `Fingerprint`;
- `SispCredentials`;
- `PaymentRequest`;
- `CallbackPayload`;
- `PaymentBuilder`;
- `Sisp`;
- formulario HTML auto-submit.

Proximas camadas naturais:

- adapter PDO com tabelas equivalentes;
- pipeline de pagamento/callback;
- idempotencia, retries e tentativas;
- controllers Slim/Laravel/Symfony;
- reconciliacao via transaction-status API.
