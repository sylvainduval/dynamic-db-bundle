<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Domain;

use SylvainDuval\DynamicDbBundle\Domain\Options\DatabaseOptionsInterface;

final readonly class Database
{
	public function __construct(
		public string $name,
		public ?DatabaseOptionsInterface $options = null,
	) {}
}
