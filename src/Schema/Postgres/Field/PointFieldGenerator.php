<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\Postgres\Field;

use InvalidArgumentException;
use SylvainDuval\DynamicDbBundle\Domain\Field\FieldInterface;
use SylvainDuval\DynamicDbBundle\Domain\Field\Point;
use SylvainDuval\DynamicDbBundle\Schema\Postgres\FieldGeneratorInterface;

/**
 * @internal
 */
final class PointFieldGenerator implements FieldGeneratorInterface
{
	public function generateFieldDefinition(FieldInterface $field): string
	{
		if (!$field instanceof Point) {
			throw new InvalidArgumentException('Expected Point, found ' . $field::class);
		}

		$fieldDefinition = $field->name . ' ' . $this->generateFieldType($field);

		$fieldDefinition .= $field->nullable ? ' NULL' : ' NOT NULL';

		return $fieldDefinition;
	}

	public function generateFieldDefaultValue(FieldInterface $field): string
	{
		return '';
	}

	public function generateFieldType(FieldInterface $field): string
	{
		//TODO: GEOGRAPHY(POINT, 4326) for SRID supports
		return 'GEOGRAPHY(POINT)';
	}
}
