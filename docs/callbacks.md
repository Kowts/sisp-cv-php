# Callbacks

O callback confirma a resposta do gateway e deve ser tratado antes de mostrar
qualquer resultado definitivo ao cliente.

## Fluxo recomendado

1. Receber `POST` no URL configurado em `urlMerchantResponse`.
2. Criar `CallbackPayload::fromPost($_POST)`.
3. Validar fingerprint com `validateCallback`.
4. Encontrar a transacao por `merchantRef` e `merchantSession`.
5. Actualizar estado local apenas se a assinatura for valida.
6. Redireccionar para uma pagina de resultado da aplicacao.

## Estados

O core mapeia mensagens conhecidas de sucesso para `completed`, erros para
`failed` e respostas ainda inconclusivas para `pending`.

## Regras de seguranca

- rejeite callbacks com fingerprint invalido;
- nao confie apenas em parametros visiveis no browser;
- guarde o payload redigido para auditoria;
- nao grave PAN completo, CVV ou dados sensiveis.
