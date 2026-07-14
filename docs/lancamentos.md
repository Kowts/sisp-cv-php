# Lançamentos

Releases usam tags `v*`.

Antes de publicar:

```bash
composer validate --strict
composer check
composer docs:api:check
composer audit
```

O workflow de release cria:

- arquivo Composer `.zip`;
- SBOM CycloneDX;
- checksums SHA-256;
- atestações de proveniência;
- GitHub Release com notas automáticas.

Enquanto o pacote estiver abaixo de `1.0.0`, fixe versões exatas em produção e
leia o changelog antes de atualizar.
