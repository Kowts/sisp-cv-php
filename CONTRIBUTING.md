# Contribuir

Obrigado por ajudar a melhorar o ecossistema PHP de Cabo Verde.

## Preparação

```bash
composer install
composer check
```

Use PHP 8.1 ou superior. Código, comentários, mensagens e documentação devem
usar português europeu (PT-PT). Use termos ingleses apenas quando forem nomes
oficiais de tecnologias, parâmetros SISP ou APIs.

Antes de abrir uma alteração, leia a documentação afectada e confirme que a
mudança preserva a separação entre core e bridges. Para alterações de esquema,
valide SQLite, MySQL/MariaDB e PostgreSQL no CI.

## Regras

- escreva testes para alterações de comportamento;
- mantenha a API principal independente de frameworks;
- não inclua tokens, `posAutCode`, dados reais de cartão, recibos reais ou dados pessoais;
- documente alterações de fluxo de pagamento, callback, idempotência ou persistência;
- mantenha bridges Laravel, Symfony e Yii2 como camadas finas sobre o core;
- siga PSR-12 e acrescente PHPDoc quando o tipo não for evidente em PHP;
- abra uma issue antes de alterações incompatíveis ou novas dependências obrigatórias.

## Pull requests

- mantenha cada pull request focado num problema ou melhoria;
- explique o comportamento anterior, o novo comportamento e o risco de
  compatibilidade;
- acrescente ou actualize testes e documentação no mesmo conjunto de alterações;
- indique os comandos executados e os que dependem do CI;
- nunca anexe segredos, dados de cartão, payloads reais ou dados pessoais.

## Convenções de documentação

- escreva instruções accionáveis e explique o motivo das decisões de segurança;
- use valores fictícios e variáveis de ambiente nos exemplos;
- identifique limitações conhecidas, sobretudo no gateway e na reconciliação;
- regenere a referência pública com `composer docs:api` quando a API mudar.

A cobertura mínima inicial é definida pelo CI e deve subir progressivamente.
Não reduza o limiar apenas para aceitar código sem testes.
