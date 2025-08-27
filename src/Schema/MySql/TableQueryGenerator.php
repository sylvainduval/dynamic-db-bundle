<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\MySql;

use SylvainDuval\DynamicDbBundle\Domain;
use SylvainDuval\DynamicDbBundle\Domain\Enum\MySql\IndexType;
use SylvainDuval\DynamicDbBundle\Domain\Options\MySql\IndexOptions;
use SylvainDuval\DynamicDbBundle\Schema\TableQueryGeneratorInterface;

/**
 * @internal
 */
class TableQueryGenerator implements TableQueryGeneratorInterface
{
	public function __construct(
		private readonly FieldQueryGenerator $fieldGenerator
	) {}

	public function generateCreateTable(Domain\Table $table): string
	{
		$query = \sprintf(
			'CREATE %s TABLE %s (',
			isset($table->options->temporary) && $table->options->temporary === true ? 'TEMPORARY' : '',
			$table->name
		);

		$fieldsList = [];
		foreach ($table->fields as $field) {
			$fieldsList[] = $this->fieldGenerator->generateFieldDefinition($field);
		}
		$query .= \implode(', ', $fieldsList);

		return ')' . $query;
	}

	public function generateDeleteTable(Domain\Table $table): string
	{
		return \sprintf(
			'DROP %s TABLE %s',
			isset($table->options->temporary) && $table->options->temporary === true ? 'TEMPORARY' : '',
			$table->name
		);
	}

	public function generateCreateIndex(Domain\Table $table, Domain\Index $index): string
	{
		$type = $index->options instanceof IndexOptions ? $index->options->type : IndexType::Index;

		return \sprintf(
			'ALTER TABLE %s ADD %s %s (%s)',
			$table->name,
			$type->value,
			$index->name,
			\implode(', ', $index->fieldNames),
		);
	}

	public function generateDeleteIndex(Domain\Table $table, string $indexName): string
	{
		return \sprintf(
			'ALTER TABLE %s DROP INDEX %s',
			$table->name,
			$indexName,
		);
	}
}
