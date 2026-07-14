# Troubleshooting

## Fingerprint invalido

Verifique `posAutCode`, amount, timestamp, currency, transaction code,
`merchantRef` e `merchantSession`. Qualquer espaco ou arredondamento diferente
altera o fingerprint.

## Callback nao encontra transacao

Confirme que o pedido foi criado com persistencia e que `merchantRef` e
`merchantSession` do callback correspondem a tentativa enviada ao gateway.

## Formulario nao redirecciona

O HTML auto-submit precisa de navegacao full-page. Se estiver a usar `fetch`,
devolva os campos ao frontend e submeta um formulario real no browser.

## Composer falha em PHP 7.4

O pacote exige PHP 8.1+. Actualize o PHP local ou valide via GitHub Actions.
