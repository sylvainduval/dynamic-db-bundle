<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Domain\Field;

final readonly class Json implements FieldInterface
{
	/**
	 * @param ?array<mixed> $default
	 */
	public function __construct(
		public string $name,
		public bool $nullable = false,
		public ?array $default = null,
	) {}

	public function getName(): string
	{
		return $this->name;
	}
}
