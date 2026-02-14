<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\Postgres\Field;

use InvalidArgumentException;
use SylvainDuval\DynamicDbBundle\Domain\Field\FieldInterface;
use SylvainDuval\DynamicDbBundle\Domain\Field\Json;
use SylvainDuval\DynamicDbBundle\Domain\Options\Postgres\Field\JsonOptions;
use SylvainDuval\DynamicDbBundle\Schema\Postgres\FieldGeneratorInterface;

/**
 * @internal
 */
final class JsonFieldGenerator implements FieldGeneratorInterface
{
	use FieldGeneratorTrait;

	public function generateFieldDefinition(FieldInterface $field): string
	{
		if (!$field instanceof Json) {
			throw new InvalidArgumentException('Expected Json, found ' . $field::class);
		}

		$fieldDefinition = $field->name . ' ' . $this->generateFieldType($field);

		$fieldDefinition .= $field->nullable ? ' NULL' : ' NOT NULL';

		$fieldDefinition .= $this->generateFieldDefaultValue($field);

		return $fieldDefinition;
	}

	public function generateFieldDefaultValue(FieldInterface $field): string
	{
		if (!$field instanceof Json) {
			throw new InvalidArgumentException('Expected Json, found ' . $field::class);
		}

		if ($field->options !== null && !$field->options instanceof JsonOptions) {
			throw new InvalidArgumentException('Expected JsonOptions, found ' . $field->options::class);
		}

		if ($field->options?->default === null && $field->nullable) {
			return ' DEFAULT NULL';
		}
		if ($field->options?->default !== null) {
			return ' DEFAULT \'' . $this->escapeSingleQuote(\json_encode($field->options->default, JSON_THROW_ON_ERROR)) . '\'';
		}

		return '';
	}

	public function generateFieldType(FieldInterface $field): string
	{
		return 'JSON';
	}
}
