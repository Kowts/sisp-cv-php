# Frameworks

As bridges são opcionais e não adicionam regra de negócio.

## Laravel

O Service Provider regista `Kowts\Sisp\Sisp` e o alias `sisp`. A configuração
padrão vive em `config/sisp.php`.

## Symfony

O Bundle regista `kowts_sisp.config` e `kowts_sisp.client`. O alias público para
injecção por tipo e `Kowts\Sisp\Sisp`.

## Yii2

`SispComponent` constroi o cliente a partir de uma propriedade `config` e delega
chamadas ao core. Em produção, configure a persistência explicitamente.
