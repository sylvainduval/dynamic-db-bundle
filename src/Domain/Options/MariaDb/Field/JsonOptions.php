<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Domain\Options\MariaDb\Field;

use SylvainDuval\DynamicDbBundle\Domain\Options\FieldOptionsInterface;

readonly class JsonOptions implements FieldOptionsInterface
{
	/**
	 * @param ?array<mixed> $default
	 */
	public function __construct(
		public ?array $default = null,
	) {}
}
