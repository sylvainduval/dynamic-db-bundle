<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\DependencyInjection;

use Psr\Container\ContainerInterface;
use SylvainDuval\DynamicDbBundle\Domain\Configuration;
use SylvainDuval\DynamicDbBundle\DynamicDbBundle;

/**
 * @phpstan-import-type ConfigurationArray from Configuration
 */
final class DynamicDbExtension
{
	/**
	 * @param ConfigurationArray $config
	 */
	public function register(array $config, ContainerInterface $container): void
	{
		/** @var ConfigurationArray $defaultConfig */
		$defaultConfig = require __DIR__ . '/../Resources/config/default.php';
		$mergedConfig = \array_merge($defaultConfig, $config);

		if (\method_exists($container, 'set') === false) {
			throw new \Exception('Container must implements set method');
		}
		$container->set('dynamic_db_bundle', function () use ($mergedConfig) {
			return new DynamicDbBundle($mergedConfig);
		});
	}
}
