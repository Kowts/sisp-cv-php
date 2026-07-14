# Persistência PDO

`PdoTransactionStore` cria tabelas automaticamente quando `autoMigrate` está
ativo em `SispConfig`.

O esquema é suportado em SQLite, MySQL/MariaDB e PostgreSQL. A biblioteca usa o
driver de `PDO::ATTR_DRIVER_NAME` para escolher a definição da chave primária e
o PostgreSQL recebe o identificador criado por `RETURNING id`.

Tabelas criadas:

- `sisp_transactions`;
- `sisp_transaction_attempts`;
- `sisp_payment_intents`;
- `sisp_transaction_logs`;
- `sisp_request_metadata`;
- `sisp_blacklist`;
- `sisp_rate_limits`.

## Ligações

```php
// SQLite
$pdo = new PDO('sqlite:/var/app/sisp.sqlite');

// MySQL ou MariaDB
$pdo = new PDO('mysql:host=127.0.0.1;dbname=sisp;charset=utf8mb4', $user, $password);

// PostgreSQL
$pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=sisp', $user, $password);
```

Configure o PDO para lançar exceções:

```php
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
```

## Migrações da aplicação

Para deixar a gestão do esquema a cargo do framework, desative a migração
automática e aplique as instruções devolvidas por `SispSchema::statements()` no
processo de migrações da aplicação. Indique o driver PDO usado pela ligação.

```php
$driver = (string) $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
$statements = SispSchema::statements($driver);
```

```php
$sisp = SispFactory::create(SispConfig::fromArray([
    // Credenciais SISP.
    'pdo' => $pdo,
    'autoMigrate' => false,
]));
```

As credenciais, callbacks e dados de pagamento não devem ser usados como dados
de teste numa base de dados partilhada.
