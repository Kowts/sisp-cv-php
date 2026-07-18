# Troubleshooting

## Fingerprint inválido

Confirme `posAutCode`, montante, timestamp, moeda, transaction code,
`merchantRef` e `merchantSession`. Um espaço, arredondamento diferente ou
separador decimal incorreto altera o fingerprint. Confirme também que o valor
enviado ao gateway corresponde ao valor persistido e que o relógio está
sincronizado.

## Callback não encontra transação

Verifique que o pedido foi criado com persistência antes do redireccionamento e
que referência e sessão do callback correspondem à tentativa enviada. Não crie
uma nova encomenda a partir de um callback desconhecido; registe o evento e
reconcilie-o.

## Formulário não redirecciona

O HTML auto-submit precisa de navegação de página inteira. Se usar uma SPA,
devolva os campos ao frontend e submeta um formulário real. Verifique também a
CSP da página, que pode bloquear o script de submissão.

## PDO não cria tabelas

Confirme que o DSN usa `sqlite`, `mysql`, `pgsql` ou `sqlsrv`, que a extensão PDO do motor
está instalada e que o utilizador de base de dados tem permissão para criar as
tabelas. Em ambientes controlados, execute migrações manualmente e defina
`autoMigrate` como `false`.

## Composer falha em PHP 7.4

O pacote exige PHP 8.1+. Atualize o PHP local ou valide através do GitHub
Actions.

## Transação fica pendente

Não force sucesso. Procure o callback, verifique registos redigidos e consulte
o canal oficial disponível para o comerciante. Depois siga a política de
[Reconciliação](reconciliacao.md).
