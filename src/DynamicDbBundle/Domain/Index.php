<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Domain;

use InvalidArgumentException;
use SylvainDuval\DynamicDbBundle\Domain\Options\IndexOptionsInterface;

final readonly class Index
{
	/**
	 * @param non-empty-string[] $fieldNames
	 */
	public function __construct(
		public string $name,
		public array $fieldNames = [],
		public ?IndexOptionsInterface $options = null,
	) {
		if ($fieldNames === []) {
			throw new InvalidArgumentException(
				\sprintf(
					'Fields list cannot be empty.',
				)
			);
		}
		foreach ($this->fieldNames as $fieldName) {
			if (!\is_string($fieldName) || \trim($fieldName) === '') {
				throw new InvalidArgumentException(
					\sprintf(
						'Each element of $fieldNames must be a non empty string.',
					)
				);
			}
		}
	}
}
