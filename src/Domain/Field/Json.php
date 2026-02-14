<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Domain\Field;

use SylvainDuval\DynamicDbBundle\Domain\Options\FieldOptionsInterface;

final readonly class Json implements FieldInterface
{
	public function __construct(
		public string $name,
		public bool $nullable = false,
		public ?FieldOptionsInterface $options = null,
	) {}

	public function getName(): string
	{
		return $this->name;
	}
}
