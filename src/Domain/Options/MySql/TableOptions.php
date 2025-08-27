<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Domain\Options\MySql;

use SylvainDuval\DynamicDbBundle\Domain\Options\TableOptionsInterface;

final readonly class TableOptions implements TableOptionsInterface
{
	public function __construct(
		public bool $temporary = false,
	) {}
}
