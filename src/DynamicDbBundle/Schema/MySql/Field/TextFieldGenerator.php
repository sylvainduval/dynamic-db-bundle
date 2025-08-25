<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\MySql\Field;

use InvalidArgumentException;
use SylvainDuval\DynamicDbBundle\Domain\Field\FieldInterface;
use SylvainDuval\DynamicDbBundle\Domain\Field\TextField;
use SylvainDuval\DynamicDbBundle\Schema\FieldDefinitionGeneratorInterface;

/**
 * @internal
 */
final class TextFieldGenerator implements FieldDefinitionGeneratorInterface
{
	public function generateFieldDefinition(FieldInterface $field): string
	{
		if (!$field instanceof TextField) {
			throw new InvalidArgumentException('Expected ' . $field::class);
		}

		$fieldDefinition = $field->name . ' ' . $this->generateFieldType($field);

		$fieldDefinition .= $field->nullable ? ' NULL' : ' NOT NULL';

		if ($field->default === null && $field->nullable) {
			$fieldDefinition .= ' DEFAULT NULL';
		}
		if ($field->default !== null) {
			$fieldDefinition .= ' DEFAULT \'' . \addslashes($field->default) . '\'';
		}

		return $fieldDefinition;
	}

	private function generateFieldType(TextField $field): string
	{
		$textTypes = [
			255 => 'TINYTEXT',
			65535 => 'TEXT',
			16777215 => 'MEDIUMTEXT',
			4294967295 => 'LONGTEXT',
		];

		if ($field->length <= 0) {
			throw new InvalidArgumentException('Length must be a positive integer.');
		}

		if ($field->length <= 65535) {
			if ($field->length <= 255 && $field->fixedLength) {
				return "CHAR({$field->length})";
			}

			return "VARCHAR({$field->length})";
		}

		foreach ($textTypes as $limit => $type) {
			if ($field->length <= $limit) {
				return $type;
			}
		}

		throw new InvalidArgumentException("Length too large for supported TEXT types.");
	}
}
