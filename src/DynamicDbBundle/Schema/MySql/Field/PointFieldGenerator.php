<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\MySql\Field;

use InvalidArgumentException;
use SylvainDuval\DynamicDbBundle\Domain\Field\FieldInterface;
use SylvainDuval\DynamicDbBundle\Domain\Field\Point;
use SylvainDuval\DynamicDbBundle\Schema\FieldDefinitionGeneratorInterface;

/**
 * @internal
 */
final class PointFieldGenerator implements FieldDefinitionGeneratorInterface
{
	public function generateFieldDefinition(FieldInterface $field): string
	{
		if (!$field instanceof Point) {
			throw new InvalidArgumentException('Expected Point, found ' . $field::class);
		}

		$fieldDefinition = $field->name . ' POINT';

		$fieldDefinition .= $field->nullable ? ' NULL' : ' NOT NULL';

		return $fieldDefinition;
	}
}
