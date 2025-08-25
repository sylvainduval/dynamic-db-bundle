<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Domain\Field;

final readonly class Json implements FieldInterface
{
	public function __construct(
		public string $name,
		public bool $nullable = false,
		/** @var ?array<mixed> $default */
		public ?array $default = null,
	) {}

	public function getName(): string
	{
		return $this->name;
	}
}
