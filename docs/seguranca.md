# Seguranca

- O token e `base64(sha512(posAutCode))`.
- Fingerprints usam concatenacao exacta de campos e montantes em milesimos.
- Comparacao de callback usa `hash_equals` sobre digest HMAC para reduzir vazamento por timing.
- Nao grave dados de cartao no payload local.
- Use HTTPS no gateway e no callback.
