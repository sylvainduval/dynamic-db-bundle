<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Domain\Options\MySql;

use SylvainDuval\DynamicDbBundle\Domain\Options\DatabaseOptionsInterface;

readonly class DatabaseOptions implements DatabaseOptionsInterface
{
	public function __construct(
		public ?string $charset = null,
		public ?string $collation = null,
		public ?string $comment = null,
	) {}
}
