<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Definition;
use SylvainDuval\DynamicDbBundle\DynamicDbBundle;

final class DynamicDbExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $defaultConfig = require __DIR__ . '/../Resources/config/default.php';
        $mergedConfig = array_merge($defaultConfig, ...$configs);

        $container->setParameter('dynamic_db.config', $mergedConfig);

        // 3. Déclare le service principal
        $definition = new Definition(DynamicDbBundle::class);
        $definition->setArgument(0, $mergedConfig); // injecte la config

        $container->setDefinition(DynamicDbBundle::class, $definition);
        $container->setAlias('dynamic_db_bundle', DynamicDbBundle::class);
    }
}