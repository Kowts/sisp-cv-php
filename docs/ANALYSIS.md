# Análise dos repositórios referência externa SISP

## O que os dois repositórios têm em comum

Ambos modelam o SISP/Vinti4 como um fluxo assinado:

1. o `posAutCode` vira `token` com `base64(sha512(posAutCode))`;
2. o pedido de pagamento concatena `token`, `timeStamp`, `amount` em milésimos, `merchantRef`, `merchantSession`, `posID`, `currency` e `transactionCode`;
3. o callback concatena 16 campos em ordem fixa e compara o fingerprint em tempo constante;
4. o reembolso usa fingerprint próprio, com `clearingPeriod` a 4 dígitos e `transactionID` a 8 dígitos;
5. valores monetários nunca devem depender de multiplicação float simples; são convertidos decimalmente para milésimos.

## Laravel

O pacote Laravel é uma integração completa: service provider, facade, controllers, views, migrations, models, invoices, blacklist, rate limit, retry, idempotência e comandos de reconciliação.

Pontos fortes para replicar:

- migrations e auditoria de transações;
- `sisp_transaction_attempts`, que evita callbacks antigos sobrescreverem tentativas novas;
- idempotência com `sisp_payment_intents`;
- pipeline de callback com validação de fingerprint antes de qualquer actualização crítica.

## Node

O pacote Node separa melhor o domínio do framework. A arquitectura é hexagonal: core/contracts, domain, application, infrastructure e presentation. Isto é a melhor referência para uma biblioteca PHP pura.

Pontos fortes para replicar:

- API `createSisp`/`Sisp` com builders;
- adapters opcionais para frameworks;
- storage por porta, com Knex/Prisma como implementações;
- testes de paridade via golden vectors.

## Decisão para este PHP

Este pacote implementa primeiro o núcleo portável:

- `SispAmount`;
- `Fingerprint`;
- `SispCredentials`;
- `PaymentRequest`;
- `CallbackPayload`;
- `PaymentBuilder`;
- `Sisp`;
- formulário HTML auto-submit.

Próximas camadas naturais:

- adapter PDO com tabelas equivalentes;
- pipeline de pagamento/callback;
- idempotência, retries e tentativas;
- controllers Slim/Laravel/Symfony;
- reconciliação via transaction-status API.
