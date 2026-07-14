# Guia de Produção

Checklist mínima antes de activar pagamentos reais:

- confirmar contrato e credenciais com a entidade adquirente;
- definir `SISP_POS_ID`, `SISP_POS_AUT_CODE`, `SISP_URL` e `SISP_CALLBACK_URL`;
- usar HTTPS público no callback;
- configurar persistência PDO;
- executar `sisp migrate` ou migrações equivalentes da aplicação;
- validar o fluxo em ambiente de testes;
- registar callbacks, tentativas e estados finais sem dados sensíveis;
- ter rotina para reconciliar transações pendentes.

## Operação

Execute periodicamente um processo de reconciliação quando a aplicação tiver
cliente de transaction-status configurado. Até lá, use relatórios internos para
identificar transações `pending` antigas e confirmar manualmente no portal do
adquirente.
