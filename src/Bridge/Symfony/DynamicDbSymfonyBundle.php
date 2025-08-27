<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Bridge\Symfony;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use SylvainDuval\DynamicDbBundle\DependencyInjection\DynamicDbExtension;


class DynamicDbSymfonyBundle extends Bundle {
	public function getContainerExtension(): ?ExtensionInterface
    {
        return new DynamicDbExtension();
    }
}
