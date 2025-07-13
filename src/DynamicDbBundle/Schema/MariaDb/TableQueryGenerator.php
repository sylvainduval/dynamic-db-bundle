<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\MariaDb;

use SylvainDuval\DynamicDbBundle\Domain;
use SylvainDuval\DynamicDbBundle\Domain\Options\MariaDb\TableOptions;
use SylvainDuval\DynamicDbBundle\Schema\MySql\TableQueryGenerator as MySqlTableQueryGenerator;

/**
 * @internal
 */
final class TableQueryGenerator extends MySqlTableQueryGenerator
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

		$query .= ')';

		if ($table->options instanceof TableOptions) {
			$options = $table->options;
			if ($options->engine !== null) {
				$query .= ' ENGINE=' . $options->engine;
			}
		}

		return $query;
	}
}
