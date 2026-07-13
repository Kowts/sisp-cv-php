# Guia de Producao

Checklist minima:

- Definir `SISP_POS_ID`, `SISP_POS_AUT_CODE`, `SISP_URL` e `SISP_CALLBACK_URL`.
- Usar HTTPS publico no callback.
- Configurar PDO persistente para auditoria.
- Executar migrations antes do primeiro pagamento.
- Monitorizar callbacks invalidos e transacoes pendentes.
