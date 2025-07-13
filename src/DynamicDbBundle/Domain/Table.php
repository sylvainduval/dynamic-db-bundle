<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Domain;

use InvalidArgumentException;
use SylvainDuval\DynamicDbBundle\Domain\Field\FieldInterface;
use SylvainDuval\DynamicDbBundle\Domain\Options\TableOptionsInterface;

final readonly class Table
{
	/**
	 * @param array<FieldInterface> $fields
	 */
	public function __construct(
		public Database $database,
		public string $name,
		public ?TableOptionsInterface $options = null,
		public array $fields = [],
	) {
		foreach ($this->fields as $field) {
			if (!$field instanceof FieldInterface) {
				throw new InvalidArgumentException(
					\sprintf(
						'Each element of $fields must implement FieldInterface, %s given.',
						\get_debug_type($field)
					)
				);
			}
		}
	}
}
