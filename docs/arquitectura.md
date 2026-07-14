# Arquitectura

O pacote separa o dominio SISP/Vinti4 das integracoes de framework.

```mermaid
flowchart TD
    A["Aplicacao"] --> B["SispFactory / SispConfig"]
    B --> C["Sisp core"]
    C --> D["PaymentBuilder"]
    C --> E["Fingerprint validator"]
    C --> F["TransactionStore"]
    F --> G["PDO / InMemory"]
    C --> H["HTML auto-submit"]
    H --> I["Gateway SISP/Vinti4"]
    I --> J["Callback"]
    J --> E
    E --> F
```

## Camadas

- `Domain`: objectos de valor, estados, codigos e montantes.
- `Contract`: portas para persistencia e futuras integracoes externas.
- `Application`: builders e accoes de caso de uso.
- `Infrastructure`: fingerprints, HTML, PDO e suporte operacional.
- `Bridge`: adaptadores Laravel, Symfony e Yii2.

As bridges nao contem regra de negocio. Elas apenas convertem configuracao do
framework para `SispConfig` e registam `Kowts\Sisp\Sisp` no container.

## Decisoes

- fingerprints e montantes ficam no core para terem o mesmo comportamento em
  PHP puro e em frameworks;
- persistencia e opcional, mas recomendada em producao;
- `SispSchema` usa SQL simples e actualmente e validado em SQLite;
- MySQL/PostgreSQL devem entrar no CI depois da camada de schema ficar
  completamente portavel.
