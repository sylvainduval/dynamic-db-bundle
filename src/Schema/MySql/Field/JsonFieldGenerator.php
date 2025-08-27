<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\MySql\Field;

use InvalidArgumentException;
use SylvainDuval\DynamicDbBundle\Domain\Field\FieldInterface;
use SylvainDuval\DynamicDbBundle\Domain\Field\Json;
use SylvainDuval\DynamicDbBundle\Schema\FieldDefinitionGeneratorInterface;

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

		$fieldDefinition = $field->name . ' JSON';

		$fieldDefinition .= $field->nullable ? ' NULL' : ' NOT NULL';

		if ($field->default === null && $field->nullable) {
			$fieldDefinition .= ' DEFAULT NULL';
		}
		if ($field->default !== null) {
			$fieldDefinition .= ' DEFAULT \'' . $this->escapeSingleQuote(\json_encode($field->default, JSON_THROW_ON_ERROR)) . '\'';
		}

		return $fieldDefinition;
	}
}
