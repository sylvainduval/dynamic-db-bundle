<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\Postgres;

use SylvainDuval\DynamicDbBundle\Domain;
use SylvainDuval\DynamicDbBundle\Schema\FieldQueryGeneratorInterface;

/**
 * @internal
 */
final class FieldQueryGenerator implements FieldQueryGeneratorInterface
{
	private FieldGeneratorFactory $fieldGeneratorFactory;

	public function __construct()
	{
		$this->fieldGeneratorFactory = new FieldGeneratorFactory();
	}

	public function generateCreateField(Domain\Table $table, Domain\Field\FieldInterface $field): string
	{
		$fieldDefinition = $this->fieldGeneratorFactory->getGenerator($field)->generateFieldDefinition($field);

		return \sprintf('ALTER TABLE %s ADD %s', $table->name, $fieldDefinition);
	}

	public function generateRenameField(Domain\Table $table, string $fromFieldName, string $toFieldName): string
	{
		return \sprintf('ALTER TABLE %s RENAME COLUMN %s TO %s', $table->name, $fromFieldName, $toFieldName);
	}

	public function generateChangeField(Domain\Table $table, Domain\Field\FieldInterface $fromField, Domain\Field\FieldInterface $toField): string
	{
		$fieldType = $this->fieldGeneratorFactory->getGenerator($toField)->generateFieldType($toField);
		$default = $this->fieldGeneratorFactory->getGenerator($toField)->generateFieldDefaultValue($toField);
		//TODO: drop or add null constraint if changed

		$alterType = \sprintf(' ALTER COLUMN %s TYPE %s', $fromField->getName(), $fieldType);
		$alterDefault = $default === '' ? '' : \sprintf(', ALTER COLUMN %s SET %s', $fromField->getName(), $default);

		return 'ALTER TABLE ' . $table->name . $alterType . $alterDefault;
	}

	public function generateFieldDefinition(Domain\Field\FieldInterface $field): string
	{
		return $this->fieldGeneratorFactory->getGenerator($field)->generateFieldDefinition($field);
	}

	public function generateDeleteField(Domain\Table $table, string $fieldName): string
	{
		return \sprintf('ALTER TABLE %s DROP COLUMN %s', $table->name, $fieldName);
	}
}
