<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\MySql\Field;

use InvalidArgumentException;
use SylvainDuval\DynamicDbBundle\Domain\Field\Boolean;
use SylvainDuval\DynamicDbBundle\Domain\Field\FieldInterface;
use SylvainDuval\DynamicDbBundle\Schema\FieldDefinitionGeneratorInterface;

/**
 * @internal
 */
final class BooleanFieldGenerator implements FieldDefinitionGeneratorInterface
{
	public function generateFieldDefinition(FieldInterface $field): string
	{
		if (!$field instanceof Boolean) {
			throw new InvalidArgumentException('Expected Boolean, found ' . $field::class);
		}

		$fieldDefinition = $field->name . ' TINYINT(1)';

		$fieldDefinition .= $field->nullable ? ' NULL' : ' NOT NULL';

		if ($field->default === null && $field->nullable) {
			$fieldDefinition .= ' DEFAULT NULL';
		}
		if ($field->default !== null) {
			$fieldDefinition .= ' DEFAULT ' . (int) $field->default;
		}

		//TODO: ajouter un check
		//$fieldDefinition .= ', CHECK (' . $field->name . ' IN (0, 1))';

		return $fieldDefinition;
	}
}
