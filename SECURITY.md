# Seguranca

Nao publique vulnerabilidades, credenciais ou dados de pagamento numa issue.

Use o separador **Security** do repositorio GitHub para relatorios privados.
Inclua a versao afectada, impacto, forma de reproducao com dados ficticios e,
se possivel, uma proposta de correcao.

Nunca envie:

- `posAutCode`, tokens, passwords, secrets ou chaves de API;
- PAN completo, CVV, PIN, dados reais de cartao ou recibos reais;
- dados pessoais de clientes;
- URLs internas, logs ou payloads sem anonimizar.

Use fixtures artificiais e reduza os exemplos ao minimo. Ate a versao `1.0.0`,
fixe uma versao exacta do pacote em producao e reveja o changelog antes de
actualizar.
