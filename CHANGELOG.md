# Changelog

Todas as alterações relevantes serão registadas neste ficheiro.

O formato segue [Keep a Changelog](https://keepachangelog.com/pt-PT/1.1.0/) e
o projecto adopta [Versionamento Semântico](https://semver.org/lang/pt-BR/).

## [Não publicado]

## [0.2.0] - 2026-07-14

### Adicionado

- Suporte de persistência PDO para MySQL/MariaDB e PostgreSQL, além de SQLite.
- Testes de integração para os três motores em serviços efémeros no GitHub Actions.

### Alterado

- `SispSchema` escolhe a definição do esquema pelo driver PDO.
- PostgreSQL recebe o identificador da transação criada por `RETURNING id`.

## [0.1.0] - 2026-07-14

### Adicionado

- Núcleo PHP puro para pagamentos SISP/Vinti4.
- Fingerprints de pagamento, callback e refund.
- Conversão decimal exata para milésimos.
- Payload 3DS em Base64.
- Persistência PDO inicial para transações, tentativas, intenções, registos,
  metadados, listas de bloqueio e limites de pedidos.
- Integrações para Laravel, Symfony e Yii2.
- CLI `sisp` com `doctor`, `migrate` e `reconcile`.
- Documentação, exemplos, testes e fluxos de trabalho do GitHub.
