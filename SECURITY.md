# Segurança

Não publique vulnerabilidades, credenciais ou dados de pagamento numa issue.

Use o separador **Security** do repositório GitHub para relatórios privados.
Inclua a versão afectada, impacto, forma de reprodução com dados fictícios e,
se possível, uma proposta de correcção. Não abra uma issue pública para uma
vulnerabilidade ainda não corrigida.

## Âmbito

Reporte problemas que possam expor segredos, permitir validar um callback sem a
credencial correta, alterar indevidamente o estado de uma transação, executar
código inesperado ou revelar dados sensíveis em logs, exemplos ou artefactos.

O pacote não opera o gateway SISP/Vinti4. Problemas de credenciais, contratos,
regras bancárias ou indisponibilidade do adquirente devem seguir os canais
oficiais da entidade contratada.

Nunca envie:

- `posAutCode`, tokens, passwords, secrets ou chaves de API;
- PAN completo, CVV, PIN, dados reais de cartão ou recibos reais;
- dados pessoais de clientes;
- URLs internas, logs ou payloads sem anonimizar.

Use fixtures artificiais e reduza os exemplos ao mínimo. Até à versão `1.0.0`,
fixe uma versão exata do pacote em produção e reveja o changelog antes de
atualizar.
