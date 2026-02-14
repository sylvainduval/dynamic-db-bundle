<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Domain\Options\Postgres\Field;

use SylvainDuval\DynamicDbBundle\Domain\Options\FieldOptionsInterface;

final readonly class JsonOptions implements FieldOptionsInterface
{
	/**
	 * @param ?array<mixed> $default
	 */
	public function __construct(
		public ?array $default = null,
	) {}
}
