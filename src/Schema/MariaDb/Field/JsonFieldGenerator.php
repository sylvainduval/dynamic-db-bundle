<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\MariaDb\Field;

use InvalidArgumentException;
use SylvainDuval\DynamicDbBundle\Domain\Field\FieldInterface;
use SylvainDuval\DynamicDbBundle\Domain\Field\Json;
use SylvainDuval\DynamicDbBundle\Schema\FieldDefinitionGeneratorInterface;
use SylvainDuval\DynamicDbBundle\Schema\MySql\Field\FieldGeneratorTrait;

/**
 * @internal
 */
final class JsonFieldGenerator implements FieldDefinitionGeneratorInterface
{
	use FieldGeneratorTrait;

	public function generateFieldDefinition(FieldInterface $field): string
	{
		if (!$field instanceof Json) {
			throw new InvalidArgumentException('Expected Json, found ' . $field::class);
		}

		$fieldDefinition = $field->name . ' LONGTEXT';

		$fieldDefinition .= $field->nullable ? ' NULL' : ' NOT NULL';

		if ($field->default === null && $field->nullable) {
			$fieldDefinition .= ' DEFAULT NULL';
		}
		if ($field->default !== null) {
			$fieldDefinition .= ' DEFAULT \'' . $this->escapeSingleQuote(\json_encode($field->default, JSON_THROW_ON_ERROR)) . '\'';
		}

		$fieldDefinition .= ' CHECK (JSON_VALID(' . $field->name . '))';

		return $fieldDefinition;
	}
}
