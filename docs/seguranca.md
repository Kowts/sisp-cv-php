# Segurança

O pacote trata assinaturas e persistência local; a aplicação continua
responsável por segredos, acesso à base de dados, HTTPS, autorização e operação
do checkout.

## Credenciais e fingerprints

`posAutCode` é usado para derivar o token do fingerprint. Guarde-o num gestor
de segredos ou na configuração protegida do ambiente. Não o exponha em HTML,
JavaScript, respostas JSON, exceções, capturas de ecrã, commits ou issues.

O fingerprint do pedido depende de uma ordem fixa de campos. O callback é
comparado em tempo constante. Uma validação bem-sucedida prova a integridade dos
campos assinados, não prova por si só que a encomenda deve ser entregue: aplique
as regras de estado e de negócio da aplicação.

## Dados proibidos em documentação e logs

Nunca registe ou publique PAN completo, CVV, PIN, credenciais, tokens, recibos
reais ou dados pessoais desnecessários. Use referências fictícias, e-mails de
teste e valores anonimizados em exemplos, testes e tickets.

O `PdoTransactionStore` guarda apenas os campos técnicos necessários para
localizar e acompanhar a transação. Remove tokens, fingerprints, payloads 3DS,
PAN, recibos e mensagens detalhadas do callback antes de os escrever em PDO.
Se a aplicação tiver uma obrigação contratual de conservar informação adicional,
faça-o fora do core, com uma finalidade documentada, controlo de acesso e prazo
de retenção explícito.

## Superfície HTTP

- use HTTPS no gateway, callback e páginas de resultado;
- aceite apenas o método esperado no callback;
- limite tamanho de pedido e aplique rate limiting no perímetro da aplicação;
- responda depressa ao callback e trate chamadas repetidas como normais;
- separe páginas autenticadas do endpoint de notificação do gateway.

## Operação

Restrinja o utilizador PDO aos privilégios necessários, rode credenciais quando
houver suspeita de exposição e acompanhe fingerprints inválidos, callbacks
desconhecidos, transações pendentes e picos de repetição. Consulte também
[SECURITY.md](../SECURITY.md) para reportar uma vulnerabilidade.
