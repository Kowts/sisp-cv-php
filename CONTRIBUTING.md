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

## Regras

- escreva testes para alterações de comportamento;
- mantenha a API principal independente de frameworks;
- não inclua tokens, `posAutCode`, dados reais de cartão, recibos reais ou dados pessoais;
- documente alterações de fluxo de pagamento, callback, idempotência ou persistência;
- mantenha bridges Laravel, Symfony e Yii2 como camadas finas sobre o core;
- siga PSR-12 e acrescente PHPDoc quando o tipo não for evidente em PHP;
- abra uma issue antes de alterações incompatíveis ou novas dependências obrigatórias.

A cobertura mínima inicial é definida pelo CI e deve subir progressivamente.
Não reduza o limiar apenas para aceitar código sem testes.
