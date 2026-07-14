# Pagamentos

O pedido de pagamento contém os campos pedidos pelo gateway, incluindo `posID`,
`merchantRef`, `merchantSession`, `amount`, `currency`, `timeStamp`,
`transactionCode` e `fingerprint`. Trate o pedido como dados assinados: depois
de criado, não altere campos que participam no fingerprint.

## Montantes e referências

- envie montantes como strings decimais com ponto, por exemplo `1500.00`;
- não use valores já multiplicados, vírgulas, símbolos monetários ou `float`
  como origem de cálculo comercial;
- gere referências curtas, únicas e rastreáveis pela aplicação;
- persista a encomenda e os identificadores antes de entregar o HTML ao cliente.

## Formulário auto-submit

`renderPaymentForm($request)` devolve HTML com submissão automática para a URL
configurada. O destino recebe `FingerPrint`, `TimeStamp` e
`FingerPrintVersion` na query string, e os restantes campos no formulário.

Devolva a resposta como `text/html` e permita navegação de página inteira. Uma
política CSP restritiva pode bloquear o script de submissão; nesse caso, use um
botão de continuidade ou ajuste a política para a página de pagamento.

## SPA e frontend separado

O backend deve criar e validar o pedido. Pode devolver os campos para o
frontend, mas o frontend deve criar e submeter um `<form>` normal. Nunca envie
`posAutCode`, token derivado, credenciais ou detalhes internos de persistência.

## 3DS

Quando `is3DSec` for `'1'`, os dados de cliente são codificados no payload
`purchaseRequest`. Use apenas dados necessários para o fluxo, valide-os na
aplicação e evite registá-los sem uma necessidade operacional e base legal.

## Resultado visível ao cliente

O regresso do browser não substitui o callback. Mostre uma página intermédia de
"pagamento em confirmação" até a aplicação ter validado e guardado o estado.
Quando houver dúvida, mantenha a transação em `pending` e reconcilie-a.
