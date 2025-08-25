<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Domain\Field;

final readonly class Boolean implements FieldInterface
{
	public function __construct(
		public string $name,
		public bool $nullable = false,
		public ?bool $default = null,
	) {}

	public function getName(): string
	{
		return $this->name;
	}
}
