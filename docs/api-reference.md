# Referencia automatica da API

> Ficheiro gerado automaticamente. Nao edite manualmente.
>
> Para regenerar: `composer docs:api`.

Esta referencia lista simbolos publicos em `src/` e os metodos publicos declarados.

## Indice

- [`Kowts\Sisp\Application\Action\BuildPaymentRequest`](#kowtssispapplicationactionbuildpaymentrequest)
- [`Kowts\Sisp\Application\Action\BuildPurchaseRequest`](#kowtssispapplicationactionbuildpurchaserequest)
- [`Kowts\Sisp\Application\Builder\PaymentBuilder`](#kowtssispapplicationbuilderpaymentbuilder)
- [`Kowts\Sisp\Bridge\Laravel\SispServiceProvider`](#kowtssispbridgelaravelsispserviceprovider)
- [`Kowts\Sisp\Bridge\Symfony\SispBundle`](#kowtssispbridgesymfonysispbundle)
- [`Kowts\Sisp\Bridge\Symfony\SispExtension`](#kowtssispbridgesymfonysispextension)
- [`Kowts\Sisp\Bridge\Yii2\SispBootstrap`](#kowtssispbridgeyii2sispbootstrap)
- [`Kowts\Sisp\Bridge\Yii2\SispComponent`](#kowtssispbridgeyii2sispcomponent)
- [`Kowts\Sisp\Config\SispConfig`](#kowtssispconfigsispconfig)
- [`Kowts\Sisp\Contract\PaymentIntentStore`](#kowtssispcontractpaymentintentstore)
- [`Kowts\Sisp\Contract\TransactionStore`](#kowtssispcontracttransactionstore)
- [`Kowts\Sisp\Domain\Amount\SispAmount`](#kowtssispdomainamountsispamount)
- [`Kowts\Sisp\Domain\TransactionCode`](#kowtssispdomaintransactioncode)
- [`Kowts\Sisp\Domain\TransactionStatus`](#kowtssispdomaintransactionstatus)
- [`Kowts\Sisp\Domain\ValueObject\CallbackPayload`](#kowtssispdomainvalueobjectcallbackpayload)
- [`Kowts\Sisp\Domain\ValueObject\PaymentRequest`](#kowtssispdomainvalueobjectpaymentrequest)
- [`Kowts\Sisp\Domain\ValueObject\SispCredentials`](#kowtssispdomainvalueobjectsispcredentials)
- [`Kowts\Sisp\Domain\ValueObject\TransactionRecord`](#kowtssispdomainvalueobjecttransactionrecord)
- [`Kowts\Sisp\Infrastructure\Http\AutoSubmitForm`](#kowtssispinfrastructurehttpautosubmitform)
- [`Kowts\Sisp\Infrastructure\Persistence\InMemoryPaymentIntentStore`](#kowtssispinfrastructurepersistenceinmemorypaymentintentstore)
- [`Kowts\Sisp\Infrastructure\Persistence\InMemoryTransactionStore`](#kowtssispinfrastructurepersistenceinmemorytransactionstore)
- [`Kowts\Sisp\Infrastructure\Persistence\PdoPaymentIntentStore`](#kowtssispinfrastructurepersistencepdopaymentintentstore)
- [`Kowts\Sisp\Infrastructure\Persistence\PdoTransactionStore`](#kowtssispinfrastructurepersistencepdotransactionstore)
- [`Kowts\Sisp\Infrastructure\Persistence\SispSchema`](#kowtssispinfrastructurepersistencesispschema)
- [`Kowts\Sisp\Infrastructure\Security\Fingerprint`](#kowtssispinfrastructuresecurityfingerprint)
- [`Kowts\Sisp\Sisp`](#kowtssispsisp)
- [`Kowts\Sisp\SispFactory`](#kowtssispsispfactory)
- [`Kowts\Sisp\Support\CountryCodeMapper`](#kowtssispsupportcountrycodemapper)
- [`Kowts\Sisp\Support\Generators`](#kowtssispsupportgenerators)

## `Kowts\Sisp\Application\Action\BuildPaymentRequest`

- Tipo: Classe
- Ficheiro: `src/Application/Action/BuildPaymentRequest.php`

### Metodos publicos

#### `__construct()`

```php
public function __construct(SispCredentials $credentials, string $transactionCode = '1')
```

#### `handle()`

```php
public function handle(array $data): PaymentRequest
```


## `Kowts\Sisp\Application\Action\BuildPurchaseRequest`

- Tipo: Classe
- Ficheiro: `src/Application/Action/BuildPurchaseRequest.php`

### Metodos publicos

#### `handle()`

```php
public static function handle(array $customer, ?DateTimeInterface $now = null): string
```


## `Kowts\Sisp\Application\Builder\PaymentBuilder`

- Tipo: Classe
- Ficheiro: `src/Application/Builder/PaymentBuilder.php`

### Metodos publicos

#### `__construct()`

```php
public function __construct(BuildPaymentRequest $buildPaymentRequest)
```

#### `amount()`

```php
public function amount($amount): self
```

#### `merchantRef()`

```php
public function merchantRef(string $merchantRef): self
```

#### `merchantSession()`

```php
public function merchantSession(string $merchantSession): self
```

#### `timeStamp()`

```php
public function timeStamp(string $timeStamp): self
```

#### `currency()`

```php
public function currency(string $currency): self
```

#### `transactionCode()`

```php
public function transactionCode(string $transactionCode): self
```

#### `token()`

```php
public function token(string $token): self
```

#### `entityCode()`

```php
public function entityCode(string $entityCode): self
```

#### `referenceNumber()`

```php
public function referenceNumber(string $referenceNumber): self
```

#### `locale()`

```php
public function locale(string $locale): self
```

#### `customerEmail()`

```php
public function customerEmail(string $email): self
```

#### `customerCountry()`

```php
public function customerCountry(string $country): self
```

#### `customerCity()`

```php
public function customerCity(string $city): self
```

#### `customerAddress()`

```php
public function customerAddress(string $address): self
```

#### `customerPostalCode()`

```php
public function customerPostalCode(string $postalCode): self
```

#### `customerPhone()`

```php
public function customerPhone(string $phone): self
```

#### `build()`

```php
public function build(): PaymentRequest
```


## `Kowts\Sisp\Bridge\Laravel\SispServiceProvider`

- Tipo: Classe
- Ficheiro: `src/Bridge/Laravel/SispServiceProvider.php`

### Metodos publicos

#### `register()`

```php
public function register(): void
```

#### `boot()`

```php
public function boot(): void
```


## `Kowts\Sisp\Bridge\Symfony\SispBundle`

- Tipo: Classe
- Ficheiro: `src/Bridge/Symfony/SispBundle.php`

### Metodos publicos

#### `getContainerExtension()`

```php
public function getContainerExtension(): SispExtension
```


## `Kowts\Sisp\Bridge\Symfony\SispExtension`

- Tipo: Classe
- Ficheiro: `src/Bridge/Symfony/SispExtension.php`

### Metodos publicos

#### `load()`

```php
public function load(array $configs, ContainerBuilder $container): void
```


## `Kowts\Sisp\Bridge\Yii2\SispBootstrap`

- Tipo: Classe
- Ficheiro: `src/Bridge/Yii2/SispBootstrap.php`

### Metodos publicos

#### `bootstrap()`

```php
public function bootstrap($app): void
```


## `Kowts\Sisp\Bridge\Yii2\SispComponent`

- Tipo: Classe
- Ficheiro: `src/Bridge/Yii2/SispComponent.php`

### Metodos publicos

#### `getClient()`

```php
public function getClient(): Sisp
```

#### `__call()`

```php
public function __call($name, $arguments)
```


## `Kowts\Sisp\Config\SispConfig`

- Tipo: Classe
- Ficheiro: `src/Config/SispConfig.php`

### Metodos publicos

#### `fromArray()`

```php
public static function fromArray(array $data): self
```

#### `credentials()`

```php
public function credentials(): SispCredentials
```

#### `transactionCode()`

```php
public function transactionCode(): string
```

#### `pdo()`

```php
public function pdo(): ?PDO
```

#### `transactionStore()`

```php
public function transactionStore(): ?TransactionStore
```

#### `autoMigrate()`

```php
public function autoMigrate(): bool
```


## `Kowts\Sisp\Contract\PaymentIntentStore`

- Tipo: Interface
- Ficheiro: `src/Contract/PaymentIntentStore.php`

### Metodos publicos

#### `reserve()`

```php
public function reserve(string $key, string $status = 'reserved'): void
```

#### `link()`

```php
public function link(string $key, int $transactionId): void
```

#### `find()`

```php
public function find(string $key): ?array
```


## `Kowts\Sisp\Contract\TransactionStore`

- Tipo: Interface
- Ficheiro: `src/Contract/TransactionStore.php`

### Metodos publicos

#### `storePaymentRequest()`

```php
public function storePaymentRequest(PaymentRequest $request): TransactionRecord
```

#### `findByMerchantIdentifiers()`

```php
public function findByMerchantIdentifiers(string $merchantRef, string $merchantSession): ?TransactionRecord
```

#### `applyCallback()`

```php
public function applyCallback(TransactionRecord $transaction, CallbackPayload $payload, string $status): TransactionRecord
```


## `Kowts\Sisp\Domain\Amount\SispAmount`

- Tipo: Classe
- Ficheiro: `src/Domain/Amount/SispAmount.php`

### Metodos publicos

#### `toThousandths()`

```php
public static function toThousandths($amount): int
```

#### `toCents()`

```php
public static function toCents($amount): int
```

#### `fromCents()`

```php
public static function fromCents($cents): float
```


## `Kowts\Sisp\Domain\TransactionCode`

- Tipo: Classe
- Ficheiro: `src/Domain/TransactionCode.php`

## `Kowts\Sisp\Domain\TransactionStatus`

- Tipo: Classe
- Ficheiro: `src/Domain/TransactionStatus.php`

## `Kowts\Sisp\Domain\ValueObject\CallbackPayload`

- Tipo: Classe
- Ficheiro: `src/Domain/ValueObject/CallbackPayload.php`

### Metodos publicos

#### `__construct()`

```php
public function __construct(array $data)
```

#### `fromPost()`

```php
public static function fromPost(array $post): self
```

#### `toFormFields()`

```php
public function toFormFields(): array
```


## `Kowts\Sisp\Domain\ValueObject\PaymentRequest`

- Tipo: Classe
- Ficheiro: `src/Domain/ValueObject/PaymentRequest.php`

### Metodos publicos

#### `__construct()`

```php
public function __construct(array $data)
```

#### `toFormFields()`

```php
public function toFormFields(): array
```


## `Kowts\Sisp\Domain\ValueObject\SispCredentials`

- Tipo: Classe
- Ficheiro: `src/Domain/ValueObject/SispCredentials.php`

### Metodos publicos

#### `__construct()`

```php
public function __construct(array $data)
```


## `Kowts\Sisp\Domain\ValueObject\TransactionRecord`

- Tipo: Classe
- Ficheiro: `src/Domain/ValueObject/TransactionRecord.php`

### Metodos publicos

#### `__construct()`

```php
public function __construct(int $id, string $merchantRef, string $merchantSession, $amount, string $currency, string $transactionCode, string $status, ?string $gatewayTransactionId = null, array $payload = [])
```


## `Kowts\Sisp\Infrastructure\Http\AutoSubmitForm`

- Tipo: Classe
- Ficheiro: `src/Infrastructure/Http/AutoSubmitForm.php`

### Metodos publicos

#### `render()`

```php
public static function render(string $action, array $fields, string $title = 'Redirecting to SISP'): string
```


## `Kowts\Sisp\Infrastructure\Persistence\InMemoryPaymentIntentStore`

- Tipo: Classe
- Ficheiro: `src/Infrastructure/Persistence/InMemoryPaymentIntentStore.php`

### Metodos publicos

#### `reserve()`

```php
public function reserve(string $key, string $status = 'reserved'): void
```

#### `link()`

```php
public function link(string $key, int $transactionId): void
```

#### `find()`

```php
public function find(string $key): ?array
```


## `Kowts\Sisp\Infrastructure\Persistence\InMemoryTransactionStore`

- Tipo: Classe
- Ficheiro: `src/Infrastructure/Persistence/InMemoryTransactionStore.php`

### Metodos publicos

#### `storePaymentRequest()`

```php
public function storePaymentRequest(PaymentRequest $request): TransactionRecord
```

#### `findByMerchantIdentifiers()`

```php
public function findByMerchantIdentifiers(string $merchantRef, string $merchantSession): ?TransactionRecord
```

#### `applyCallback()`

```php
public function applyCallback(TransactionRecord $transaction, CallbackPayload $payload, string $status): TransactionRecord
```


## `Kowts\Sisp\Infrastructure\Persistence\PdoPaymentIntentStore`

- Tipo: Classe
- Ficheiro: `src/Infrastructure/Persistence/PdoPaymentIntentStore.php`

### Metodos publicos

#### `__construct()`

```php
public function __construct(PDO $pdo, bool $autoMigrate = true)
```

#### `reserve()`

```php
public function reserve(string $key, string $status = 'reserved'): void
```

#### `link()`

```php
public function link(string $key, int $transactionId): void
```

#### `find()`

```php
public function find(string $key): ?array
```


## `Kowts\Sisp\Infrastructure\Persistence\PdoTransactionStore`

- Tipo: Classe
- Ficheiro: `src/Infrastructure/Persistence/PdoTransactionStore.php`

### Metodos publicos

#### `__construct()`

```php
public function __construct(PDO $pdo, bool $autoMigrate = true)
```

#### `storePaymentRequest()`

```php
public function storePaymentRequest(PaymentRequest $request): TransactionRecord
```

#### `findByMerchantIdentifiers()`

```php
public function findByMerchantIdentifiers(string $merchantRef, string $merchantSession): ?TransactionRecord
```

#### `applyCallback()`

```php
public function applyCallback(TransactionRecord $transaction, CallbackPayload $payload, string $status): TransactionRecord
```


## `Kowts\Sisp\Infrastructure\Persistence\SispSchema`

- Tipo: Classe
- Ficheiro: `src/Infrastructure/Persistence/SispSchema.php`

### Metodos publicos

#### `migrate()`

```php
public static function migrate(PDO $pdo): void
```

#### `statements()`

```php
public static function statements(): array
```


## `Kowts\Sisp\Infrastructure\Security\Fingerprint`

- Tipo: Classe
- Ficheiro: `src/Infrastructure/Security/Fingerprint.php`

### Metodos publicos

#### `sha512Base64()`

```php
public static function sha512Base64(string $content): string
```

#### `computeToken()`

```php
public static function computeToken(string $posAutCode): string
```

#### `payment()`

```php
public static function payment(string $token, array $data): string
```

#### `callback()`

```php
public static function callback(string $token, CallbackPayload $payload): string
```

#### `refund()`

```php
public static function refund(string $token, array $data): string
```

#### `validateCallback()`

```php
public static function validateCallback(string $token, CallbackPayload $payload): bool
```

#### `constantTimeEquals()`

```php
public static function constantTimeEquals(string $expected, string $actual): bool
```


## `Kowts\Sisp\Sisp`

- Tipo: Classe
- Ficheiro: `src/Sisp.php`

### Metodos publicos

#### `__construct()`

```php
public function __construct(SispCredentials $credentials, string $transactionCode = '1', ?TransactionStore $transactionStore = null)
```

#### `payment()`

```php
public function payment(): PaymentBuilder
```

#### `buildRequestPayload()`

```php
public function buildRequestPayload(array $data): PaymentRequest
```

#### `createPayment()`

```php
public function createPayment(array $data): PaymentRequest
```

#### `validateCallback()`

```php
public function validateCallback(CallbackPayload $payload): bool
```

#### `handleCallback()`

```php
public function handleCallback(CallbackPayload $payload): ?TransactionRecord
```

#### `gatewayFormAction()`

```php
public function gatewayFormAction(PaymentRequest $request): string
```

#### `renderPaymentForm()`

```php
public function renderPaymentForm(PaymentRequest $request, string $title = 'Redirecting to SISP'): string
```


## `Kowts\Sisp\SispFactory`

- Tipo: Classe
- Ficheiro: `src/SispFactory.php`

### Metodos publicos

#### `create()`

```php
public static function create(SispConfig $config): Sisp
```


## `Kowts\Sisp\Support\CountryCodeMapper`

- Tipo: Classe
- Ficheiro: `src/Support/CountryCodeMapper.php`

### Metodos publicos

#### `toNumeric()`

```php
public static function toNumeric(string $alpha2Code): string
```


## `Kowts\Sisp\Support\Generators`

- Tipo: Classe
- Ficheiro: `src/Support/Generators.php`

### Metodos publicos

#### `merchantReference()`

```php
public static function merchantReference(?DateTimeInterface $date = null): string
```

#### `merchantSession()`

```php
public static function merchantSession(?DateTimeInterface $date = null): string
```

#### `timeStamp()`

```php
public static function timeStamp(?DateTimeInterface $date = null): string
```
