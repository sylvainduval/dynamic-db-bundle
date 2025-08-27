<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Domain\Field;

final readonly class Text implements FieldInterface
{
	public function __construct(
		public string $name,
		public int $length = 254,
		public bool $fixedLength = false,
		public bool $nullable = false,
		public ?string $default = null,
	) {}

	public function getName(): string
	{
		return $this->name;
	}
}
