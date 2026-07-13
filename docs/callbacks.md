# Callbacks

O callback deve ser convertido para `CallbackPayload::fromPost($_POST)` e validado com `validateCallback`.

Quando o cliente foi criado com storage PDO ou `TransactionStore`, `handleCallback` procura a transacao por `merchantRef` e `merchantSession`, valida o fingerprint e actualiza o estado local.

Callbacks invalidos retornam `null` e nao alteram persistencia.
