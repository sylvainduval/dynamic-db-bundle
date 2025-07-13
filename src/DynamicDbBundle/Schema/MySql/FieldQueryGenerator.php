<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\MySql;

use InvalidArgumentException;
use RuntimeException;
use SylvainDuval\DynamicDbBundle\Domain;
use SylvainDuval\DynamicDbBundle\Domain\Field\TextField;
use SylvainDuval\DynamicDbBundle\Schema\FieldQueryGeneratorInterface;

/**
 * @internal
 */
class FieldQueryGenerator implements FieldQueryGeneratorInterface
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
		$fieldDefinition = '';
		if ($fromField instanceof TextField && $toField instanceof TextField) {
			$fieldDefinition = $this->generateTextFieldDefinition($toField);
		}
		if ($fieldDefinition === '') {
			throw new RuntimeException('Field not supported');
		}

		return \sprintf('ALTER TABLE %s MODIFY %s', $table->name, $fieldDefinition);
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

		$fieldDefinition .= $field->nullable ? ' NULL' : ' NOT NULL';

		if ($field->default === null && $field->nullable) {
			$fieldDefinition .= ' DEFAULT NULL';
		}
		if ($field->default !== null) {
			$fieldDefinition .= ' DEFAULT \'' . \addslashes($field->default) . '\'';
		}

		return $fieldDefinition;
	}

	private function generateTextFieldType(TextField $field): string
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
