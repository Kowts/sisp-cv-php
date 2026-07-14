# Callbacks

O callback confirma a resposta do gateway e deve ser tratado antes de mostrar
qualquer resultado definitivo ao cliente.

## Fluxo recomendado

1. Receber `POST` no URL configurado em `urlMerchantResponse`.
2. Criar `CallbackPayload::fromPost($_POST)`.
3. Validar fingerprint com `validateCallback`.
4. Encontrar a transação por `merchantRef` e `merchantSession`.
5. Actualizar estado local apenas se a assinatura for válida.
6. Redireccionar para uma página de resultado da aplicação.

## Estados

O core mapeia mensagens conhecidas de sucesso para `completed`, erros para
`failed` e respostas ainda inconclusivas para `pending`.

## Regras de segurança

- rejeite callbacks com fingerprint inválido;
- não confie apenas em parâmetros visíveis no browser;
- guarde o payload redigido para auditoria;
- não grave PAN completo, CVV ou dados sensíveis.
