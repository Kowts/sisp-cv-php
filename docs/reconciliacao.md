# Reconciliação

Reconciliação e o processo de confirmar transações locais que ficaram
`pending` depois do callback ou de falhas de rede.

## Estado actual

O CLI fornece `sisp reconcile` como gancho operacional. A consulta remota ao
serviço de transaction-status deve ser adicionada por um cliente HTTP dedicado,
sem alterar as bridges Laravel, Symfony ou Yii2.

## Recomendação operacional

- liste transações `pending` antigas;
- confirme estado no portal ou API oficial do adquirente;
- actualize apenas transações cujo identificador local corresponda ao
  `merchantRef` e `merchantSession`;
- mantenha log de quem reconciliou e quando;
- nunca trate ausência de callback como sucesso.

## Futuro cliente transaction-status

Quando for implementado, o cliente deve receber credenciais próprias do portal,
timeouts curtos, retries controlados e respostas normalizadas para
`completed`, `failed` ou `pending`.
