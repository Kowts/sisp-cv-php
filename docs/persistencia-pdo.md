# Persistência PDO

Ao receber `pdo` em `SispConfig`, `SispFactory` cria um `PdoTransactionStore`.
O core mantém `autoMigrate` activo por compatibilidade quando a opção não é
fornecida. A configuração Laravel distribuída pelo pacote, porém, define
`SISP_AUTO_MIGRATE=false` por predefinição. Em produção execute um processo de
migração controlado e desactive sempre `autoMigrate` depois da instalação.

Os stores PDO forçam `PDO::ERRMODE_EXCEPTION`. Um erro de ligação, SQL ou
serialização interrompe a operação em vez de deixar uma tentativa de pagamento
parcialmente persistida sem aviso.

## Motores suportados

| Motor | DSN de exemplo | Extensão PHP |
| --- | --- | --- |
| SQLite | `sqlite:/var/app/sisp.sqlite` | `pdo_sqlite` |
| MySQL/MariaDB | `mysql:host=127.0.0.1;dbname=sisp;charset=utf8mb4` | `pdo_mysql` |
| PostgreSQL | `pgsql:host=127.0.0.1;port=5432;dbname=sisp` | `pdo_pgsql` |
| SQL Server | `sqlsrv:Server=sqlserver.example,1433;Database=sisp;Encrypt=yes;TrustServerCertificate=no` | `pdo_sqlsrv` |

O esquema escolhe a coluna de chave primária de acordo com o driver. No
PostgreSQL, a criação de transação usa `RETURNING id`; no SQL Server usa
`OUTPUT INSERTED.id`; em SQLite e MySQL/MariaDB usa o identificador devolvido
pelo PDO. No SQL Server, o esquema usa `IDENTITY`, `VARCHAR(MAX)` para payloads
e `OBJECT_ID` para criar tabelas sem as recriar.

Para SQL Server, instale a extensão `pdo_sqlsrv` e o Microsoft ODBC Driver para
SQL Server na mesma máquina que executa PHP. O DSN acima exige um certificado
confiado pelo sistema; `TrustServerCertificate=1` deve ficar limitado a
desenvolvimento e a testes com certificados autoassinados.

## Tabelas

- `sisp_transactions`: pedido, estado e identificadores do gateway;
- `sisp_transaction_attempts`: registo da tentativa e actualização do callback;
- `sisp_payment_intents`: reserva de idempotência;
- `sisp_transaction_logs`, `sisp_request_metadata`, `sisp_blacklist` e
  `sisp_rate_limits`: tabelas operacionais reservadas ao controlo da aplicação.

As tabelas não substituem as tabelas de encomenda, cliente, contabilidade ou
auditoria da aplicação. O core armazena payloads redigidos e não guarda tokens,
fingerprints, dados 3DS, PAN, recibos nem mensagens detalhadas de erro nestas
colunas.

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
produção. A conta da aplicação precisa apenas de acesso de leitura e escrita às
tabelas SISP; reserve `CREATE TABLE` para a conta de migração.

## Concorrência e cópias de segurança

O armazenamento agrupa a criação do pedido e da tentativa numa transação PDO.
A aplicação de callback usa uma transição condicional de `pending` para impedir
que uma execução atrasada substitua um estado final já gravado. Ainda assim, a
aplicação deve impor idempotência por encomenda e observar falhas de ligação.
Inclua as tabelas SISP nas cópias de segurança, monitorize espaço e defina uma
política de retenção para logs e payloads redigidos.
