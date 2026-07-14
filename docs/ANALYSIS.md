# Decisões de desenho

Este documento mantém o contexto das decisões estruturais do pacote. Não é um
manual de integração; para isso, consulte o [índice](indice.md).

## Core independente

O domínio, os builders, fingerprints e contratos de armazenamento não dependem
de framework. Isto permite o mesmo comportamento em PHP puro, Laravel, Symfony
e Yii2 e reduz o risco de divergência entre integrações.

## Persistência opcional

Uma integração simples pode criar pedidos sem armazenamento. Em produção, a
persistência é recomendada para associar callbacks, tentativas e idempotência a
uma encomenda local. O suporte PDO é validado em SQLite, MySQL/MariaDB e
PostgreSQL.

## Bridges finas

As bridges apenas adaptam configuração e injeção de dependências. Lógica de
pagamento, cálculo de fingerprint e transição de estado permanecem no core.

## Limites de gateway

O pacote não substitui documentação oficial, contrato de adquirência, portal de
reconciliação ou regras de liquidação. Um cliente de consulta remota só deve ser
adicionado quando existir um contrato e uma especificação oficial verificável.
