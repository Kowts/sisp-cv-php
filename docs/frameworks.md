# Frameworks

As bridges são opcionais e não adicionam regra de negócio. Todas usam o mesmo
`SispFactory`, pelo que o pedido, fingerprint, callback e persistência têm o
mesmo comportamento em qualquer framework.

## Laravel

O Service Provider regista `Kowts\Sisp\Sisp` e o alias `sisp`. A configuração
padrão vive em `config/sisp.php`.

## Symfony

O Bundle regista `kowts_sisp.config` e `kowts_sisp.client`. O alias público para
injecção por tipo e `Kowts\Sisp\Sisp`.

## Yii2

`SispComponent` constroi o cliente a partir de uma propriedade `config` e delega
chamadas ao core. Em produção, configure a persistência explicitamente.

## Persistência nas bridges

As bridges não criam ligações PDO por conta própria. Crie a ligação na aplicação
com o driver, charset, tratamento de exceções e privilégios adequados, e passe-a
na configuração do SISP. Isto mantém a política de base de dados sob controlo
da aplicação anfitriã.
