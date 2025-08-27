<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\DependencyInjection;

use SylvainDuval\DynamicDbBundle\Domain\Configuration;
use SylvainDuval\DynamicDbBundle\DynamicDbBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;

/**
 * @phpstan-import-type ConfigurationArray from Configuration
 */
final class DynamicDbExtension extends Extension
{
	/**
	 * @param ConfigurationArray $config
	 */
	public function load(array $config, ContainerBuilder $container): void
	{
		/** @var ConfigurationArray $defaultConfig */
		$defaultConfig = require __DIR__ . '/../Resources/config/default.php';
		$mergedConfig = \array_merge($defaultConfig, ...$config);

		$container->setParameter('dynamic_db.config', $mergedConfig);

		// 3. Déclare le service principal
		$definition = new Definition(DynamicDbBundle::class);
		$definition->setArgument(0, $mergedConfig); // injecte la config

		$container->setDefinition(DynamicDbBundle::class, $definition);
		$container->setAlias('dynamic_db_bundle', DynamicDbBundle::class);
	}

	public function getAlias(): string
	{
		return 'dynamic_db';
	}
}
