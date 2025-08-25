<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\Postgres\Field;

use InvalidArgumentException;
use SylvainDuval\DynamicDbBundle\Domain\Field\FieldInterface;
use SylvainDuval\DynamicDbBundle\Domain\Field\UuidField;
use SylvainDuval\DynamicDbBundle\Schema\Postgres\FieldGeneratorInterface;

/**
 * @internal
 */
final class UuidFieldGenerator implements FieldGeneratorInterface
{
	public function generateFieldDefinition(FieldInterface $field): string
	{
		if (!$field instanceof UuidField) {
			throw new InvalidArgumentException('Expected ' . $field::class);
		}

		$fieldDefinition = $field->name . ' ' . $this->generateFieldType($field);

		$fieldDefinition .= $field->nullable ? ' NULL' : ' NOT NULL';

		$fieldDefinition .= $this->generateFieldDefaultValue($field);

		return $fieldDefinition;
	}

	public function generateFieldDefaultValue(FieldInterface $field): string
	{
		if (!$field instanceof UuidField) {
			throw new InvalidArgumentException('Expected ' . $field::class);
		}

		if ($field->default === null && $field->nullable) {
			return ' DEFAULT NULL';
		}
		if ($field->default !== null) {
			return ' DEFAULT \'' . \addslashes($field->default) . '\'';
		}

		return '';
	}

	public function generateFieldType(FieldInterface $field): string
	{
		return 'UUID';
	}
}
