# Changelog

Todas as alteracoes relevantes serao registadas neste ficheiro.

O formato segue [Keep a Changelog](https://keepachangelog.com/pt-PT/1.1.0/) e
o projecto adopta [Versionamento Semantico](https://semver.org/lang/pt-BR/).

## [Nao publicado]

### Adicionado

- Nucleo PHP puro para pagamentos SISP/Vinti4.
- Fingerprints de pagamento, callback e refund.
- Conversao decimal exacta para milesimos.
- Payload 3DS em Base64.
- Persistencia PDO inicial para transacoes, tentativas, intents, logs, metadata,
  blacklist e rate limits.
- Bridges Laravel, Symfony e Yii2.
- CLI `sisp` com `doctor`, `migrate` e `reconcile`.
- Documentacao, exemplos, testes e workflows GitHub.
