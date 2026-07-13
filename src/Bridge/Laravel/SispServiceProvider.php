<?php

declare(strict_types=1);

namespace Kowts\Sisp\Bridge\Laravel;

use Illuminate\Support\ServiceProvider;
use Kowts\Sisp\Config\SispConfig;
use Kowts\Sisp\Sisp;
use Kowts\Sisp\SispFactory;

final class SispServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../../config/sisp.php', 'sisp');

        $this->app->singleton(Sisp::class, function (): Sisp {
            return SispFactory::create(SispConfig::fromArray((array) config('sisp', [])));
        });

        $this->app->alias(Sisp::class, 'sisp');
    }

    public function boot(): void
    {
        if (method_exists($this, 'publishes')) {
            $this->publishes([
                __DIR__.'/../../../config/sisp.php' => config_path('sisp.php'),
            ], 'sisp-config');
        }
    }
}
