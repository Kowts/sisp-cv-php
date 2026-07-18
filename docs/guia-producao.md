# Guia de produção

## Antes do lançamento

- confirme contrato, POS ID, credenciais, moedas e URLs com o adquirente;
- configure `SISP_POS_ID`, `SISP_POS_AUT_CODE`, `SISP_URL` e
  `SISP_CALLBACK_URL` no ambiente seguro;
- defina `SISP_AUTO_MIGRATE=false`, instale o driver PDO correcto e execute as
  migrações no motor de produção antes de expor o checkout;
- para SQL Server, instale `pdo_sqlsrv` e o Microsoft ODBC Driver, use um
  certificado TLS confiado e mantenha `TrustServerCertificate=no`;
- use HTTPS público no callback e teste DNS, firewall, TLS e redireccionamentos;
- valide criação, redireccionamento, callback válido, callback repetido, falha
  de pagamento e transação pendente no ambiente de testes do fornecedor;
- documente responsáveis, contactos de escalamento e rotina de reconciliação.

## Durante a operação

Monitorize taxa de callbacks inválidos, transações pendentes, falhas de PDO e
diferenças entre encomendas e transações. Registe referências e estados, não
dados de cartão. Trate indisponibilidade do gateway como `pending` até existir
confirmação oficial.

Os dados persistidos pelo core são redigidos. Não reintroduza tokens, payloads
3DS, PAN, recibos ou mensagens detalhadas em tabelas de auditoria, plataformas
de observabilidade ou ferramentas de erro.

## Recuperação

Quando faltar um callback, procure primeiro a transação por referência e sessão,
consulte o canal oficial disponível para o comerciante e registe quem reconciliou
o caso. Não altere estados em massa sem correspondência verificável entre a
encomenda local e o identificador do gateway.
