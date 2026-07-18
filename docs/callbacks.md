# Callbacks

O callback confirma a resposta do gateway. Deve ser um endpoint HTTPS público,
sem autenticação de sessão do cliente e capaz de receber repetições da mesma
notificação.

## Fluxo recomendado

1. Receba o `POST` no URL definido em `urlMerchantResponse`.
2. Construa `CallbackPayload::fromPost($_POST)`.
3. Valide o fingerprint com `validateCallback()`.
4. Encontre a transação por `merchantRef` e `merchantSession`.
5. Registe a notificação e aplique a transição de estado numa transação local.
6. Responda rapidamente com um código HTTP de sucesso para callbacks válidos.
7. Faça o redireccionamento ou a consulta de estado do cliente separadamente.

## Estados locais

O core mapeia mensagens de sucesso conhecidas para `completed`, falhas para
`failed` e respostas inconclusivas para `pending`. A aplicação deve tratar o
estado local como parte do seu próprio fluxo de encomenda: só entregue produto,
active serviço ou emita documento quando a regra de negócio permitir.

## Repetições e ordem

O gateway pode repetir callbacks ou o browser pode regressar antes deles. Um
callback repetido deve ser seguro: conserve o mesmo estado final, actualize a
auditoria e não duplique encomendas, recibos ou movimentos de stock. Não assuma
que as notificações chegam por ordem cronológica.

## Registo seguro

Guarde identificadores, estado, código de resposta e horário. Redija ou omita
PAN, recibos, erros detalhados, e qualquer dado pessoal que não seja necessário
para operar a transação. O payload completo não deve ser exposto em logs de
aplicação, ferramentas de erro ou issues.

`CallbackPayload::toFormFields()` existe para interoperabilidade e validação do
protocolo; não é um formato de arquivo. O armazenamento PDO usa internamente
`toSafeStorageFields()` e elimina assinatura, PAN, recibo e mensagem detalhada
antes de persistir a notificação.

## Falhas de validação

Para fingerprints inválidos, responda com `400`, não altere a encomenda e registe
um evento técnico sem segredos. Investigue referências desconhecidas e callbacks
atrasados através da rotina de reconciliação, nunca marcando sucesso por omissão.
