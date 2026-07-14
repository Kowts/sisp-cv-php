# Reconciliacao

Reconciliacao e o processo de confirmar transacoes locais que ficaram
`pending` depois do callback ou de falhas de rede.

## Estado actual

O CLI fornece `sisp reconcile` como gancho operacional. A consulta remota ao
servico de transaction-status deve ser adicionada por um cliente HTTP dedicado,
sem alterar as bridges Laravel, Symfony ou Yii2.

## Recomendacao operacional

- liste transacoes `pending` antigas;
- confirme estado no portal ou API oficial do adquirente;
- actualize apenas transacoes cujo identificador local corresponda ao
  `merchantRef` e `merchantSession`;
- mantenha log de quem reconciliou e quando;
- nunca trate ausencia de callback como sucesso.

## Futuro cliente transaction-status

Quando for implementado, o cliente deve receber credenciais proprias do portal,
timeouts curtos, retries controlados e respostas normalizadas para
`completed`, `failed` ou `pending`.
