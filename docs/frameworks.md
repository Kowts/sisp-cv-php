# Frameworks

As bridges sao opcionais e nao adicionam regra de negocio.

## Laravel

O Service Provider regista `Kowts\Sisp\Sisp` e o alias `sisp`. A configuracao
padrao vive em `config/sisp.php`.

## Symfony

O Bundle regista `kowts_sisp.config` e `kowts_sisp.client`. O alias publico para
injeccao por tipo e `Kowts\Sisp\Sisp`.

## Yii2

`SispComponent` constroi o cliente a partir de uma propriedade `config` e delega
chamadas ao core. Em producao, configure a persistencia explicitamente.
