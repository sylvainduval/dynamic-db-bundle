<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Domain\Options\MySql;

use SylvainDuval\DynamicDbBundle\Domain\Enum\MySql\IndexType;
use SylvainDuval\DynamicDbBundle\Domain\Options\IndexOptionsInterface;

readonly class IndexOptions implements IndexOptionsInterface
{
	public function __construct(
		public IndexType $type = IndexType::Index
	) {}
}
