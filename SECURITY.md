# Segurança

Não publique vulnerabilidades, credenciais ou dados de pagamento numa issue.

Use o separador **Security** do repositório GitHub para relatórios privados.
Inclua a versão afectada, impacto, forma de reprodução com dados fictícios e,
se possível, uma proposta de correcção.

Nunca envie:

- `posAutCode`, tokens, passwords, secrets ou chaves de API;
- PAN completo, CVV, PIN, dados reais de cartão ou recibos reais;
- dados pessoais de clientes;
- URLs internas, logs ou payloads sem anonimizar.

Use fixtures artificiais e reduza os exemplos ao mínimo. Até a versão `1.0.0`,
fixe uma versão exacta do pacote em produção e reveja o changelog antes de
actualizar.
