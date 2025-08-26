<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Domain\Field;

final readonly class Datetime implements FieldInterface
{
	public function __construct(
		public string $name,
		public bool $nullable = false,
		public bool $defaultCurrent = false,
	) {}

	public function getName(): string
	{
		return $this->name;
	}
}
