# SISP CV para PHP

[![Testes](https://github.com/Kowts/sisp-cv-php/actions/workflows/ci.yml/badge.svg)](https://github.com/Kowts/sisp-cv-php/actions/workflows/ci.yml)
[![Cobertura](https://img.shields.io/badge/cobertura-%E2%89%A550%25-brightgreen.svg)](https://github.com/Kowts/sisp-cv-php/actions/workflows/ci.yml)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%208-4e9a06.svg)](phpstan.neon.dist)
[![Packagist](https://img.shields.io/packagist/v/kowts/sisp-cv.svg)](https://packagist.org/packages/kowts/sisp-cv)
[![PHP](https://img.shields.io/badge/PHP-%5E8.1-777BB4.svg)](https://www.php.net/)
[![Licença](https://img.shields.io/badge/licença-MIT-blue.svg)](LICENSE)
[![Aikido package health](https://img.shields.io/badge/Aikido-package%20health-6f5bf4.svg)](https://intel.aikido.dev/packages/packagist/kowts/sisp-cv)
![Estado](https://img.shields.io/badge/estado-beta-orange.svg)

Biblioteca PHP independente de frameworks para criar pedidos de pagamento
SISP/Vinti4, gerar fingerprints, validar callbacks e manter o ciclo de vida
local da transação em SQLite, MySQL/MariaDB ou PostgreSQL.

> [!IMPORTANT]
> Este projecto não é oficial da SISP, da Vinti4, de bancos adquirentes nem de
> entidades reguladoras. A produção exige contrato, credenciais e endpoints
> oficiais fornecidos pela entidade adquirente, bem como testes no ambiente por
> ela indicado.

## O que resolve

- cria pedidos de pagamento com montantes convertidos para milésimos;
- gera o formulário HTML de redireccionamento ou expõe os campos para uma SPA;
- valida callbacks em tempo constante e actualiza o estado local;
- guarda transações, tentativas e intenções de pagamento através de PDO;
- funciona em PHP puro e disponibiliza bridges finas para Laravel, Symfony e
  Yii2;
- inclui CLI, documentação operacional, testes e releases com SBOM e checksums.

O pacote não recolhe dados de cartão, não substitui o contrato com o adquirente
e não consulta remotamente o estado de uma transação sem um cliente oficial
configurado pela aplicação.

## Requisitos

- PHP 8.1 ou superior;
- extensões `json` e `pdo`;
- um driver PDO quando existir persistência: `pdo_sqlite`, `pdo_mysql` ou
  `pdo_pgsql`;
- credenciais SISP/Vinti4 válidas, fora do código e do repositório.

## Instalação

```bash
composer require kowts/sisp-cv:^0.2
```

## Primeiro pagamento

```php
<?php

use Kowts\Sisp\Config\SispConfig;
use Kowts\Sisp\SispFactory;

require __DIR__ . '/vendor/autoload.php';

$sisp = SispFactory::create(SispConfig::fromArray([
    'posId' => getenv('SISP_POS_ID'),
    'posAutCode' => getenv('SISP_POS_AUT_CODE'),
    'url' => getenv('SISP_URL'),
    'urlMerchantResponse' => getenv('SISP_CALLBACK_URL'),
]));

$request = $sisp->payment()
    ->amount('1500.00')
    ->merchantRef('ORDER-20260714-001')
    ->merchantSession('CHECKOUT-20260714-001')
    ->build();

echo $sisp->renderPaymentForm($request);
```

`amount` aceita uma string decimal com ponto. A biblioteca converte o valor para
milésimos antes de calcular o fingerprint; não formate o montante de forma
manual nem use vírgulas como separador decimal.

## Fluxo de integração

1. A aplicação cria referências únicas de encomenda e de sessão.
2. O backend constrói e, em produção, persiste o pedido antes do redireccionamento.
3. O cliente é enviado para o gateway SISP/Vinti4.
4. O endpoint de callback valida o fingerprint e actualiza a transação local.
5. A aplicação mostra o resultado apenas depois de confirmar o estado local.
6. Transações `pending` são acompanhadas por uma rotina de reconciliação.

Consulte [Pagamentos](docs/pagamentos.md), [Callbacks](docs/callbacks.md) e
[Reconciliação](docs/reconciliacao.md) antes de ligar o pacote ao checkout.

## Persistência PDO

Passe uma ligação PDO para ativar a persistência automática. O esquema suporta
SQLite, MySQL/MariaDB e PostgreSQL.

```php
$pdo = new PDO(
    'mysql:host=127.0.0.1;dbname=sisp;charset=utf8mb4',
    getenv('SISP_DB_USER'),
    getenv('SISP_DB_PASSWORD'),
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$sisp = SispFactory::create(SispConfig::fromArray([
    'posId' => getenv('SISP_POS_ID'),
    'posAutCode' => getenv('SISP_POS_AUT_CODE'),
    'url' => getenv('SISP_URL'),
    'urlMerchantResponse' => getenv('SISP_CALLBACK_URL'),
    'pdo' => $pdo,
]));
```

O guia de [Persistência PDO](docs/persistencia-pdo.md) descreve o esquema,
migrações controladas pela aplicação e as diferenças entre motores.

## Frameworks e CLI

- [Laravel](docs/laravel.md): `Kowts\Sisp\Sisp` resolvido pelo contentor.
- [Symfony](docs/symfony.md): serviço `kowts_sisp.client`.
- [Yii2](docs/yii2.md): componente `Yii::$app->sisp`.
- `bin/sisp doctor`: valida a versão de PHP, extensões e variáveis SISP.
- `bin/sisp migrate`: cria o esquema no DSN indicado por `SISP_DB_DSN`.

## Segurança

Nunca envie `posAutCode`, tokens, PAN completo, CVV, PIN, recibos reais ou
dados pessoais para o browser, repositório, issues ou logs. Use HTTPS no
gateway e callback, mantenha segredos no gestor de configuração da aplicação e
trate callbacks inválidos como tentativas não confiáveis.

Leia [Segurança](docs/seguranca.md), [Guia de produção](docs/guia-producao.md)
e a política em [SECURITY.md](SECURITY.md).

## Documentação

O [índice da documentação](docs/indice.md) organiza os guias por fase de
integração. Inclui a [API prática](docs/api.md), a
[referência automática](docs/api-reference.md),
[exemplos executáveis](examples/README.md),
[arquitetura](docs/arquitectura.md) e instruções de
[contribuição](CONTRIBUTING.md).

## Desenvolvimento

```bash
composer install
composer check
```

O CI valida PHP 8.1 a 8.4, Windows e Linux, dependências mínimas e recentes,
Yii2 e persistência SQLite, MySQL e PostgreSQL.

## Licença

[MIT](LICENSE) © 2026 Kowts. Consulte também [NOTICE](NOTICE).
