<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Domain\Field;

final readonly class Numeric implements FieldInterface
{
	public function __construct(
		public string $name,
		public int $min = 0,
		public int $max = 254,
		public int $decimals = 0,
		public bool $autoIncrement = false,
		public bool $nullable = false,
		public float|int|null $default = null,
	) {}

	public function getName(): string
	{
		return $this->name;
	}
}
