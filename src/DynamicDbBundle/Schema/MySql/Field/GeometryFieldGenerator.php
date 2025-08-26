<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\MySql\Field;

use InvalidArgumentException;
use SylvainDuval\DynamicDbBundle\Domain\Field\FieldInterface;
use SylvainDuval\DynamicDbBundle\Domain\Field\Geometry;
use SylvainDuval\DynamicDbBundle\Schema\FieldDefinitionGeneratorInterface;

/**
 * @internal
 */
final class GeometryFieldGenerator implements FieldDefinitionGeneratorInterface
{
	public function generateFieldDefinition(FieldInterface $field): string
	{
		if (!$field instanceof Geometry) {
			throw new InvalidArgumentException('Expected Geometry, found ' . $field::class);
		}

		$fieldDefinition = $field->name . ' GEOMETRY';

		$fieldDefinition .= $field->nullable ? ' NULL' : ' NOT NULL';

		return $fieldDefinition;
	}
}
