# SISP CV para PHP

[![Testes](https://github.com/Kowts/sisp-cv-php/actions/workflows/ci.yml/badge.svg)](https://github.com/Kowts/sisp-cv-php/actions/workflows/ci.yml)
[![Cobertura](https://img.shields.io/badge/cobertura-%E2%89%A550%25-brightgreen.svg)](https://github.com/Kowts/sisp-cv-php/actions/workflows/ci.yml)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%208-4e9a06.svg)](phpstan.neon.dist)
[![PHP](https://img.shields.io/badge/PHP-%5E8.1-777BB4.svg)](https://www.php.net/)
[![Licença](https://img.shields.io/badge/licen%C3%A7a-MIT-blue.svg)](LICENSE)
[![Packagist](https://img.shields.io/packagist/v/kowts/sisp-cv.svg)](https://packagist.org/packages/kowts/sisp-cv)
![Status](https://img.shields.io/badge/status-beta-orange.svg)

Biblioteca PHP independente de frameworks para criar pedidos de pagamento
SISP/Vinti4, assinar fingerprints, validar callbacks, persistir transações e
integrar aplicações PHP puras, Laravel, Symfony e Yii2.

> [!IMPORTANT]
> Este projecto não é oficial da SISP/Vinti4. A utilização em produção exige
> credenciais válidas, contrato com a entidade adquirente, endpoints oficiais e
> testes no ambiente indicado pelo fornecedor de pagamentos.

## Funcionalidades

- token `base64(sha512(posAutCode))` e fingerprints SHA-512;
- montantes convertidos para milésimos sem multiplicação float insegura;
- builder fluente de pagamento e formulário HTML auto-submit;
- validação de callback em tempo constante;
- payload 3DS com dados de cliente;
- persistência PDO inicial para transações, tentativas e intents;
- CLI para diagnóstico, migrations e gancho de reconciliação;
- bridges opcionais Laravel, Symfony e Yii2;
- testes de paridade para fingerprints e montantes.

## Requisitos

- PHP 8.1 ou superior;
- extensões `json` e `pdo`;
- `pdo_sqlite` para testes locais SQLite;
- credenciais SISP/Vinti4 fornecidas pela entidade adquirente.

## Instalação

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

## Utilização rápida

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

## Persistência PDO

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
- Symfony regista o serviço `kowts_sisp.client`.
- Yii2 expõe `SispComponent` para `Yii::$app->sisp`.

Consulte [Laravel, Symfony e Yii2](docs/frameworks.md).

## Segurança

- nunca envie `posAutCode`, tokens, CVV, PAN completo ou credenciais para o browser;
- não guarde segredos no repositório;
- use HTTPS no callback e no gateway;
- grave logs sem dados de cartão ou dados pessoais;
- valide callbacks antes de actualizar transações locais.

Consulte [Segurança](SECURITY.md) e [docs/seguranca.md](docs/seguranca.md).

## Documentação

- [Arquitectura](docs/arquitectura.md)
- [API](docs/api.md)
- [Referência automática da API](docs/api-reference.md)
- [Pagamentos](docs/pagamentos.md)
- [Callbacks](docs/callbacks.md)
- [Idempotência](docs/idempotencia.md)
- [Persistência PDO](docs/persistencia-pdo.md)
- [Troubleshooting](docs/troubleshooting.md)
- [Segurança](docs/seguranca.md)
- [Guia de produção](docs/guia-producao.md)
- [Frameworks](docs/frameworks.md)
- [Laravel](docs/laravel.md)
- [Symfony](docs/symfony.md)
- [Yii2](docs/yii2.md)
- [Lançamentos](docs/lancamentos.md)
- [Exemplos](examples/README.md)
- [Contribuir](CONTRIBUTING.md)

## Desenvolvimento

```bash
composer install
composer check
```

O pacote exige PHP 8.1+. Em ambientes locais com PHP 7.4, use o CI ou uma
instalação PHP 8.1+ para correr PHPUnit, PHPStan e PHPCS.

## Licença

[MIT](LICENSE) © 2026 Kowts.
