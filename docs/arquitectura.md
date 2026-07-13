# Arquitectura

O pacote e dividido em nucleo PHP puro e bridges opcionais.

- `Domain`: objectos de valor, estados, codigos e montantes.
- `Contract`: portas para persistencia e integracoes externas.
- `Application`: builders e accoes de caso de uso.
- `Infrastructure`: fingerprints, HTML, PDO e suporte operacional.
- `Bridge`: adaptadores Laravel, Symfony e Yii2.

As bridges nao contem regra de negocio. Elas apenas constroem `SispConfig` e registam `Sisp` no container do framework.
