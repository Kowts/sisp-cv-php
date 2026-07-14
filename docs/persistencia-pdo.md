# Persistencia PDO

`PdoTransactionStore` cria tabelas automaticamente quando `autoMigrate` esta
activo em `SispConfig`.

Tabelas actuais:

- `sisp_transactions`;
- `sisp_transaction_attempts`;
- `sisp_payment_intents`;
- `sisp_transaction_logs`;
- `sisp_request_metadata`;
- `sisp_blacklist`;
- `sisp_rate_limits`.

## SQLite

SQLite e o driver validado actualmente no CI planeado. Use:

```php
$pdo = new PDO('sqlite:/var/app/sisp.sqlite');
```

## MySQL e PostgreSQL

Adie uso automatico de `SispSchema` nesses motores ate a camada de schema ficar
totalmente portavel. Em aplicacoes reais, converta os statements para migracoes
do seu framework e valide locks/indices no motor final.
