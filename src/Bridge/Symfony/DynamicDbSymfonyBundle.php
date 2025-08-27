<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Bridge\Symfony;

use SylvainDuval\DynamicDbBundle\DependencyInjection\DynamicDbExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DynamicDbSymfonyBundle extends Bundle
{
	public function getContainerExtension(): ?ExtensionInterface
	{
		return new DynamicDbExtension();
	}
}
