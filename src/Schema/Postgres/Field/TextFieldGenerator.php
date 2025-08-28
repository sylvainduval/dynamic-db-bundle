<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\Postgres\Field;

use InvalidArgumentException;
use SylvainDuval\DynamicDbBundle\Domain\Field\FieldInterface;
use SylvainDuval\DynamicDbBundle\Domain\Field\Text;
use SylvainDuval\DynamicDbBundle\Schema\Postgres\FieldGeneratorInterface;

/**
 * @internal
 */
final class TextFieldGenerator implements FieldGeneratorInterface
{
	use FieldGeneratorTrait;

	public function generateFieldDefinition(FieldInterface $field): string
	{
		if (!$field instanceof Text) {
			throw new InvalidArgumentException('Expected Text, found ' . $field::class);
		}

		$fieldDefinition = $field->name . ' ' . $this->generateFieldType($field);

		if ($field->nullable === false) {
			$fieldDefinition .= ' NOT NULL';
		}

		$fieldDefinition .= $this->generateFieldDefaultValue($field);

		return $fieldDefinition;
	}

	public function generateFieldDefaultValue(FieldInterface $field): string
	{
		if (!$field instanceof Text) {
			throw new InvalidArgumentException('Expected Text, found ' . $field::class);
		}

		if ($field->default === null && $field->nullable) {
			return ' DEFAULT NULL';
		}
		if ($field->default !== null) {
			return ' DEFAULT \'' . $this->escapeSingleQuote($field->default) . '\'';
		}

		return '';
	}

	public function generateFieldType(FieldInterface $field): string
	{
		if (!$field instanceof Text) {
			throw new InvalidArgumentException('Expected Text, found ' . $field::class);
		}

		if ($field->length <= 0) {
			throw new InvalidArgumentException('Length must be a positive integer.');
		}

		if ($field->length > 10 * 1024 * 1024) { //10MB
			// above 10 MB, no need to restrict
			return 'TEXT';
		}

		if ($field->fixedLength) {
			//TODO: inefficient above 255, throw InvalidArgumentException or use varchar ?
			return "CHAR({$field->length})";
		}

		return "VARCHAR({$field->length})";
	}
}
