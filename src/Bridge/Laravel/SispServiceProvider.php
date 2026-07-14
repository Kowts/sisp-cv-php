<?php

declare(strict_types=1);

namespace Kowts\Sisp\Bridge\Laravel;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Kowts\Sisp\Config\SispConfig;
use Kowts\Sisp\Sisp;
use Kowts\Sisp\SispFactory;

final class SispServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../../config/sisp.php', 'sisp');

        $this->app->singleton(Sisp::class, static function (Application $app): Sisp {
            $repository = $app->make('config');
            $config = $repository instanceof ConfigRepository ? $repository->get('sisp', []) : [];

            return SispFactory::create(SispConfig::fromArray(is_array($config) ? $config : []));
        });

        $this->app->alias(Sisp::class, 'sisp');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../../config/sisp.php' => $this->applicationConfigPath('sisp.php'),
        ], 'sisp-config');
    }

    private function applicationConfigPath(string $path): string
    {
        return $this->app->configPath($path);
    }
}
