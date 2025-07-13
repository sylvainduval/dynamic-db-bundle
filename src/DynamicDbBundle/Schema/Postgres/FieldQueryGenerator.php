<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\Postgres;

use RuntimeException;
use SylvainDuval\DynamicDbBundle\Domain;
use SylvainDuval\DynamicDbBundle\Domain\Field\TextField;
use SylvainDuval\DynamicDbBundle\Schema\FieldQueryGeneratorInterface;

/**
 * @internal
 */
final class FieldQueryGenerator implements FieldQueryGeneratorInterface
{
	public function generateCreateField(Domain\Table $table, Domain\Field\FieldInterface $field): string
	{
		$fieldDefinition = '';
		if ($field instanceof TextField) {
			$fieldDefinition = $this->generateTextFieldDefinition($field);
		}
		if ($fieldDefinition === '') {
			throw new RuntimeException('Field not supported');
		}

		return \sprintf('ALTER TABLE %s ADD %s', $table->name, $fieldDefinition);
	}

	public function generateRenameField(Domain\Table $table, string $fromFieldName, string $toFieldName): string
	{
		return \sprintf('ALTER TABLE %s RENAME COLUMN %s TO %s', $table->name, $fromFieldName, $toFieldName);
	}

	public function generateChangeField(Domain\Table $table, Domain\Field\FieldInterface $fromField, Domain\Field\FieldInterface $toField): string
	{
		$fieldType = '';
		$default = '';
		if ($fromField instanceof TextField && $toField instanceof TextField) {
			$fieldType = $this->generateTextFieldType($toField);
			$default = $this->generateTextFieldDefaultValue($toField);
			//TODO: drop or add null constraint if changed
		}
		if ($fieldType === '') {
			throw new RuntimeException('Field not supported');
		}

		$alterType = \sprintf(' ALTER COLUMN %s TYPE %s', $fromField->getName(), $fieldType);
		$alterDefault = $default === '' ? '' : \sprintf(', ALTER COLUMN %s SET %s', $fromField->getName(), $default);

		return 'ALTER TABLE ' . $table->name . $alterType . $alterDefault;
	}

	public function generateFieldDefinition(Domain\Field\FieldInterface $field): string
	{
		if ($field instanceof TextField) {
			return $this->generateTextFieldDefinition($field);
		}

		throw new RuntimeException('Field not supported');
	}

	public function generateDeleteField(Domain\Table $table, string $fieldName): string
	{
		return \sprintf('ALTER TABLE %s DROP COLUMN %s', $table->name, $fieldName);
	}

	private function generateTextFieldDefinition(TextField $field): string
	{
		$fieldDefinition = $field->name . ' ' . $this->generateTextFieldType($field);

		if ($field->nullable === false) {
			$fieldDefinition .= ' NOT NULL';
		}

		$fieldDefinition .= $this->generateTextFieldDefaultValue($field);

		return $fieldDefinition;
	}

	private function generateTextFieldDefaultValue(TextField $field): string
	{
		if ($field->default === null && $field->nullable) {
			return ' DEFAULT NULL';
		}
		if ($field->default !== null) {
			return ' DEFAULT \'' . \addslashes($field->default) . '\'';
		}

		return '';
	}

	private function generateTextFieldType(TextField $field): string
	{
		if ($field->length <= 0) {
			throw new \InvalidArgumentException('Length must be a positive integer.');
		}

		if ($field->length > 10485760) {
			// above 10 MB, no need to restrict
			return 'TEXT';
		}

		if ($field->fixedLength) {
			return "CHAR({$field->length})";
		}

		return "VARCHAR({$field->length})";
	}
}
