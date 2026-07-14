# Persistência PDO

Ao receber `pdo` em `SispConfig`, `SispFactory` cria um `PdoTransactionStore`.
Por predefinição, o esquema é criado automaticamente. Em produção, prefira um
processo de migração controlado e desative `autoMigrate` depois da instalação.

## Motores suportados

| Motor | DSN de exemplo | Extensão PHP |
| --- | --- | --- |
| SQLite | `sqlite:/var/app/sisp.sqlite` | `pdo_sqlite` |
| MySQL/MariaDB | `mysql:host=127.0.0.1;dbname=sisp;charset=utf8mb4` | `pdo_mysql` |
| PostgreSQL | `pgsql:host=127.0.0.1;port=5432;dbname=sisp` | `pdo_pgsql` |

O esquema escolhe a coluna de chave primária de acordo com o driver. No
PostgreSQL, a criação de transação usa `RETURNING id`; em SQLite e MySQL/MariaDB
usa o identificador devolvido pelo PDO.

## Tabelas

- `sisp_transactions`: pedido, estado e identificadores do gateway;
- `sisp_transaction_attempts`: registo da tentativa e actualização do callback;
- `sisp_payment_intents`: reserva de idempotência;
- `sisp_transaction_logs`, `sisp_request_metadata`, `sisp_blacklist` e
  `sisp_rate_limits`: tabelas operacionais reservadas ao controlo da aplicação.

As tabelas não substituem as tabelas de encomenda, cliente, contabilidade ou
auditoria da aplicação. Não guarde dados de cartão nem segredos nestas colunas.

## Migração controlada

```php
use Kowts\Sisp\Infrastructure\Persistence\SispSchema;

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$driver = (string) $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

foreach (SispSchema::statements($driver) as $statement) {
    $pdo->exec($statement);
}
```

Depois configure `autoMigrate` como `false`. Execute a migração com uma conta de
base de dados de privilégio limitado e teste-a no mesmo motor e versão usados em
produção.

## Concorrência e cópias de segurança

O armazenamento agrupa a criação do pedido e da tentativa numa transação PDO.
Ainda assim, a aplicação deve impor idempotência por encomenda e observar falhas
de ligação. Inclua as tabelas SISP nas cópias de segurança, monitorize espaço e
defina uma política de retenção para logs e payloads redigidos.
