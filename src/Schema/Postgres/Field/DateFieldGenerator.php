<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\Postgres\Field;

use InvalidArgumentException;
use SylvainDuval\DynamicDbBundle\Domain\Field\Date;
use SylvainDuval\DynamicDbBundle\Domain\Field\FieldInterface;
use SylvainDuval\DynamicDbBundle\Schema\Postgres\FieldGeneratorInterface;

/**
 * @internal
 */
final class DateFieldGenerator implements FieldGeneratorInterface
{
	public function generateFieldDefinition(FieldInterface $field): string
	{
		if (!$field instanceof Date) {
			throw new InvalidArgumentException('Expected Date, found ' . $field::class);
		}

		$fieldDefinition = $field->name . ' ' . $this->generateFieldType($field);

		$fieldDefinition .= $field->nullable ? ' NULL' : ' NOT NULL';

		$fieldDefinition .= $this->generateFieldDefaultValue($field);

		return $fieldDefinition;
	}

	public function generateFieldDefaultValue(FieldInterface $field): string
	{
		if (!$field instanceof Date) {
			throw new InvalidArgumentException('Expected Date, found ' . $field::class);
		}

		if ($field->defaultCurrent === false && $field->nullable === true) {
			return ' DEFAULT NULL';
		}
		if ($field->defaultCurrent === true) {
			return ' DEFAULT CURRENT_DATE';
		}

		return '';
	}

	public function generateFieldType(FieldInterface $field): string
	{
		return 'DATE';
	}
}
