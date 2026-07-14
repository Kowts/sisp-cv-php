# Guia de Producao

Checklist minima antes de activar pagamentos reais:

- confirmar contrato e credenciais com a entidade adquirente;
- definir `SISP_POS_ID`, `SISP_POS_AUT_CODE`, `SISP_URL` e `SISP_CALLBACK_URL`;
- usar HTTPS publico no callback;
- configurar persistencia PDO;
- executar `sisp migrate` ou migracoes equivalentes da aplicacao;
- validar o fluxo em ambiente de testes;
- registar callbacks, tentativas e estados finais sem dados sensiveis;
- ter rotina para reconciliar transacoes pendentes.

## Operacao

Execute periodicamente um processo de reconciliacao quando a aplicacao tiver
cliente de transaction-status configurado. Ate la, use relatórios internos para
identificar transacoes `pending` antigas e confirmar manualmente no portal do
adquirente.
