<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\Postgres;

use SylvainDuval\DynamicDbBundle\Domain;
use SylvainDuval\DynamicDbBundle\Domain\Enum\Postgres\IndexType;
use SylvainDuval\DynamicDbBundle\Domain\Options\Postgres\IndexOptions;
use SylvainDuval\DynamicDbBundle\Domain\Options\Postgres\TableOptions;
use SylvainDuval\DynamicDbBundle\Schema\TableQueryGeneratorInterface;

/**
 * @internal
 */
final class TableQueryGenerator implements TableQueryGeneratorInterface
{
	public function __construct(
		private readonly FieldQueryGenerator $fieldGenerator
	) {}

	public function generateCreateTable(Domain\Table $table): string
	{
		$query = \sprintf(
			'CREATE %s TABLE %s (',
			$table->options instanceof TableOptions && $table->options->temporary === true ? 'TEMPORARY' : '',
			$table->name
		);

		$fieldsList = [];
		foreach ($table->fields as $field) {
			$fieldsList[] = $this->fieldGenerator->generateFieldDefinition($field);
		}
		$query .= \implode(', ', $fieldsList) . ')';


		if ($table->options instanceof TableOptions) {
			$options = $table->options;
			if ($options->tablespace !== null) {
				$query .= ' TABLESPACE ' . $options->tablespace;
			}
		}

		return $query;
	}

	public function generateDeleteTable(Domain\Table $table): string
	{
		return \sprintf(
			'DROP %s TABLE %s',
			$table->options instanceof TableOptions && $table->options->temporary === true ? 'TEMPORARY' : '',
			$table->name
		);
	}

	public function generateCreateIndex(Domain\Table $table, Domain\Index $index): string
	{
		$type = $index->options instanceof IndexOptions ? $index->options->type : IndexType::Btree;
		$unique = $index->options instanceof IndexOptions ? $index->options->unique : false;

		return \sprintf(
			'CREATE %s INDEX %s ON %s USING %s (%s)',
			$unique ? 'UNIQUE' : '',
			$index->name,
			$table->name,
			$type->value,
			\implode(', ', $index->fieldNames),
		);
	}

	public function generateDeleteIndex(Domain\Table $table, string $indexName): string
	{
		return 'DROP INDEX ' . $indexName;
	}
}
