<?php

declare(strict_types=1);

namespace Kowts\Sisp\Bridge\Symfony;

use Kowts\Sisp\Config\SispConfig;
use Kowts\Sisp\Sisp;
use Kowts\Sisp\SispFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;

final class SispExtension extends Extension
{
    /**
     * @param array<int,array<string,mixed>> $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = array_replace(...array_merge([[]], $configs));

        $configDefinition = new Definition(SispConfig::class);
        $configDefinition->setFactory([SispConfig::class, 'fromArray']);
        $configDefinition->setArguments([$config]);
        $container->setDefinition('kowts_sisp.config', $configDefinition);

        $clientDefinition = new Definition(Sisp::class);
        $clientDefinition->setFactory([SispFactory::class, 'create']);
        $clientDefinition->setArguments([new Reference('kowts_sisp.config')]);
        $container->setDefinition('kowts_sisp.client', $clientDefinition);
        $container->setAlias(Sisp::class, 'kowts_sisp.client')->setPublic(true);
    }
}
