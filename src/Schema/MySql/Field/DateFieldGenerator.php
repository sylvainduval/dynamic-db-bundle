<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\MySql\Field;

use InvalidArgumentException;
use SylvainDuval\DynamicDbBundle\Domain\Field\Date;
use SylvainDuval\DynamicDbBundle\Domain\Field\FieldInterface;
use SylvainDuval\DynamicDbBundle\Schema\FieldDefinitionGeneratorInterface;

/**
 * @internal
 */
final class DateFieldGenerator implements FieldDefinitionGeneratorInterface
{
	public function generateFieldDefinition(FieldInterface $field): string
	{
		if (!$field instanceof Date) {
			throw new InvalidArgumentException('Expected Date, found ' . $field::class);
		}

		$fieldDefinition = $field->name . ' DATE';

		$fieldDefinition .= $field->nullable ? ' NULL' : ' NOT NULL';

		if ($field->defaultCurrent === false && $field->nullable) {
			$fieldDefinition .= ' DEFAULT NULL';
		}
		if ($field->defaultCurrent === true) {
			$fieldDefinition .= ' DEFAULT NOW()';
		}

		return $fieldDefinition;
	}
}
