<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\MySql\Field;

use InvalidArgumentException;
use SylvainDuval\DynamicDbBundle\Domain\Field\FieldInterface;
use SylvainDuval\DynamicDbBundle\Domain\Field\UuidField;
use SylvainDuval\DynamicDbBundle\Schema\FieldDefinitionGeneratorInterface;

/**
 * @internal
 */
final class UuidFieldGenerator implements FieldDefinitionGeneratorInterface
{
	public function generateFieldDefinition(FieldInterface $field): string
	{
		if (!$field instanceof UuidField) {
			throw new InvalidArgumentException('Expected ' . $field::class);
		}

		$fieldDefinition = $field->name . ' CHAR(36)';

		$fieldDefinition .= $field->nullable ? ' NULL' : ' NOT NULL';

		if ($field->default === null && $field->nullable) {
			$fieldDefinition .= ' DEFAULT NULL';
		}
		if ($field->default !== null) {
			$fieldDefinition .= ' DEFAULT \'' . $field->default . '\'';
		}

		return $fieldDefinition;
	}
}
