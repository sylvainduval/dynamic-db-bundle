<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\Postgres;

use SylvainDuval\DynamicDbBundle\Domain;
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
}
