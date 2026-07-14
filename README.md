# SISP CV para PHP

[![Testes](https://github.com/Kowts/sisp-cv-php/actions/workflows/ci.yml/badge.svg)](https://github.com/Kowts/sisp-cv-php/actions/workflows/ci.yml)
[![Cobertura](https://img.shields.io/badge/cobertura-%E2%89%A550%25-brightgreen.svg)](https://github.com/Kowts/sisp-cv-php/actions/workflows/ci.yml)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%208-4e9a06.svg)](phpstan.neon.dist)
[![PHP](https://img.shields.io/badge/PHP-%5E8.1-777BB4.svg)](https://www.php.net/)
[![Licenca](https://img.shields.io/badge/licen%C3%A7a-MIT-blue.svg)](LICENSE)
[![Packagist](https://img.shields.io/packagist/v/kowts/sisp-cv.svg)](https://packagist.org/packages/kowts/sisp-cv)
![Status](https://img.shields.io/badge/status-beta-orange.svg)

Biblioteca PHP independente de frameworks para criar pedidos de pagamento
SISP/Vinti4, assinar fingerprints, validar callbacks, persistir transacoes e
integrar aplicacoes PHP puras, Laravel, Symfony e Yii2.

> [!IMPORTANT]
> Este projecto nao e oficial da SISP/Vinti4. A utilizacao em producao exige
> credenciais validas, contrato com a entidade adquirente, endpoints oficiais e
> testes no ambiente indicado pelo fornecedor de pagamentos.

## Funcionalidades

- token `base64(sha512(posAutCode))` e fingerprints SHA-512;
- montantes convertidos para milesimos sem multiplicacao float insegura;
- builder fluente de pagamento e formulario HTML auto-submit;
- validacao de callback em tempo constante;
- payload 3DS com dados de cliente;
- persistencia PDO inicial para transacoes, tentativas e intents;
- CLI para diagnostico, migrations e gancho de reconciliacao;
- bridges opcionais Laravel, Symfony e Yii2;
- testes de paridade para fingerprints e montantes.

## Requisitos

- PHP 8.1 ou superior;
- extensoes `json` e `pdo`;
- `pdo_sqlite` para testes locais SQLite;
- credenciais SISP/Vinti4 fornecidas pela entidade adquirente.

## Instalacao

```bash
composer require kowts/sisp-cv
```

Para usar o ramo de desenvolvimento:

```json
{
  "repositories": [
    {"type": "vcs", "url": "https://github.com/Kowts/sisp-cv-php"}
  ],
  "require": {
    "kowts/sisp-cv": "dev-main"
  }
}
```

## Utilizacao rapida

```php
<?php

require __DIR__.'/vendor/autoload.php';

use Kowts\Sisp\Config\SispConfig;
use Kowts\Sisp\SispFactory;

$sisp = SispFactory::create(SispConfig::fromArray([
    'posId' => '90051',
    'posAutCode' => getenv('SISP_POS_AUT_CODE'),
    'url' => getenv('SISP_URL'),
    'urlMerchantResponse' => 'https://example.com/sisp/callback',
]));

$request = $sisp->payment()
    ->amount('1500')
    ->merchantRef('R'.date('YmdHis'))
    ->merchantSession('S'.date('YmdHis'))
    ->build();

echo $sisp->renderPaymentForm($request);
```

## Persistencia PDO

```php
$pdo = new PDO('sqlite:/var/app/sisp.sqlite');

$sisp = SispFactory::create(SispConfig::fromArray([
    'posId' => '90051',
    'posAutCode' => getenv('SISP_POS_AUT_CODE'),
    'url' => getenv('SISP_URL'),
    'urlMerchantResponse' => 'https://example.com/sisp/callback',
    'pdo' => $pdo,
]));

$request = $sisp->createPayment([
    'amount' => '1500',
    'merchantRef' => 'R'.date('YmdHis'),
    'merchantSession' => 'S'.date('YmdHis'),
]);
```

## Frameworks

- Laravel resolve `Kowts\Sisp\Sisp` no container e publica `config/sisp.php`.
- Symfony regista o servico `kowts_sisp.client`.
- Yii2 expoe `SispComponent` para `Yii::$app->sisp`.

Consulte [Laravel, Symfony e Yii2](docs/frameworks.md).

## Seguranca

- nunca envie `posAutCode`, tokens, CVV, PAN completo ou credenciais para o browser;
- nao guarde segredos no repositorio;
- use HTTPS no callback e no gateway;
- grave logs sem dados de cartao ou dados pessoais;
- valide callbacks antes de actualizar transacoes locais.

Consulte [Seguranca](SECURITY.md) e [docs/seguranca.md](docs/seguranca.md).

## Documentacao

- [Arquitectura](docs/arquitectura.md)
- [API](docs/api.md)
- [Referencia automatica da API](docs/api-reference.md)
- [Pagamentos](docs/pagamentos.md)
- [Callbacks](docs/callbacks.md)
- [Idempotencia](docs/idempotencia.md)
- [Persistencia PDO](docs/persistencia-pdo.md)
- [Troubleshooting](docs/troubleshooting.md)
- [Seguranca](docs/seguranca.md)
- [Guia de producao](docs/guia-producao.md)
- [Frameworks](docs/frameworks.md)
- [Laravel](docs/laravel.md)
- [Symfony](docs/symfony.md)
- [Yii2](docs/yii2.md)
- [Lancamentos](docs/lancamentos.md)
- [Exemplos](examples/README.md)
- [Contribuir](CONTRIBUTING.md)

## Desenvolvimento

```bash
composer install
composer check
```

O pacote exige PHP 8.1+. Em ambientes locais com PHP 7.4, use o CI ou uma
instalacao PHP 8.1+ para correr PHPUnit, PHPStan e PHPCS.

## Licenca

[MIT](LICENSE) © 2026 Kowts.
