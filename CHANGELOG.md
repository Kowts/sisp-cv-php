# Changelog

Todas as alterações relevantes serão registadas neste ficheiro.

O formato segue [Keep a Changelog](https://keepachangelog.com/pt-PT/1.1.0/) e
o projecto adopta [Versionamento Semântico](https://semver.org/lang/pt-BR/).

## [Não publicado]

### Adicionado

- Nucleo PHP puro para pagamentos SISP/Vinti4.
- Fingerprints de pagamento, callback e refund.
- Conversão decimal exacta para milésimos.
- Payload 3DS em Base64.
- Persistência PDO inicial para transações, tentativas, intents, logs, metadata,
  blacklist e rate limits.
- Bridges Laravel, Symfony e Yii2.
- CLI `sisp` com `doctor`, `migrate` e `reconcile`.
- Documentação, exemplos, testes e workflows GitHub.
