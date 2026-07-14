# Contribuir

Obrigado por ajudar a melhorar o ecossistema PHP de Cabo Verde.

## Preparacao

```bash
composer install
composer check
```

Use PHP 8.1 ou superior. Codigo, comentarios, mensagens e documentacao devem
usar portugues europeu (PT-PT). Use termos ingleses apenas quando forem nomes
oficiais de tecnologias, parametros SISP ou APIs.

## Regras

- escreva testes para alteracoes de comportamento;
- mantenha a API principal independente de frameworks;
- nao inclua tokens, `posAutCode`, dados reais de cartao, recibos reais ou dados pessoais;
- documente alteracoes de fluxo de pagamento, callback, idempotencia ou persistencia;
- mantenha bridges Laravel, Symfony e Yii2 como camadas finas sobre o core;
- siga PSR-12 e acrescente PHPDoc quando o tipo nao for evidente em PHP;
- abra uma issue antes de alteracoes incompatíveis ou novas dependencias obrigatorias.

A cobertura minima inicial e definida pelo CI e deve subir progressivamente.
Nao reduza o limiar apenas para aceitar codigo sem testes.
