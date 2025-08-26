<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Domain\Options\Postgres;

use SylvainDuval\DynamicDbBundle\Domain\Enum\Postgres\IndexType;
use SylvainDuval\DynamicDbBundle\Domain\Options\IndexOptionsInterface;

final readonly class IndexOptions implements IndexOptionsInterface
{
	public function __construct(
		public IndexType $type = IndexType::Btree,
		public bool $unique = false,
	) {}
}
