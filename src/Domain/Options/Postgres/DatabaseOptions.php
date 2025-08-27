<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Domain\Options\Postgres;

use SylvainDuval\DynamicDbBundle\Domain\Options\DatabaseOptionsInterface;

final readonly class DatabaseOptions implements DatabaseOptionsInterface
{
	public function __construct(
		public ?string $encoding = null,
		public ?string $owner = null,
		public ?string $lcCollate = null,
		public ?string $lcCtype = null,
		public ?string $template = null,
	) {}
}
