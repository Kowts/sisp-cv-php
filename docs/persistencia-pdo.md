# Persistência PDO

`PdoTransactionStore` cria tabelas automaticamente quando `autoMigrate` está
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

SQLite é o driver validado actualmente no CI planeado. Use:

```php
$pdo = new PDO('sqlite:/var/app/sisp.sqlite');
```

## MySQL e PostgreSQL

Adie o uso automático de `SispSchema` nesses motores até a camada de schema ficar
totalmente portável. Em aplicações reais, converta os statements para migrações
do seu framework e valide locks/índices no motor final.
