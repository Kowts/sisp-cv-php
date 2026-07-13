# Laravel

Adicione as variaveis `SISP_*` no `.env` e publique a config:

```bash
php artisan vendor:publish --tag=sisp-config
```

Depois injecte `Kowts\Sisp\Sisp` no controller ou resolva `app('sisp')`.
