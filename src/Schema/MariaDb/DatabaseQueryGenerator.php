<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\MariaDb;

use SylvainDuval\DynamicDbBundle\Domain;
use SylvainDuval\DynamicDbBundle\Domain\Options\MariaDb\DatabaseOptions;
use SylvainDuval\DynamicDbBundle\Schema\MySql\DatabaseQueryGenerator as MySqlDatabaseQueryGenerator;

final class DatabaseQueryGenerator extends MySqlDatabaseQueryGenerator
{
	public function generateCreateDatabase(Domain\Database $database): string
	{
		$query = 'CREATE DATABASE ' . $database->name;

		if ($database->options instanceof DatabaseOptions) {
			$options = $database->options;

			if ($options->charset !== null) {
				$query .= ' CHARACTER SET = \'' . $options->charset . '\'';
			}

			if ($options->collation !== null) {
				$query .= ' COLLATE = \'' . $options->collation . '\'';
			}

			if ($options->comment !== null) {
				$query .= ' COMMENT = \'' . \addslashes($options->comment) . '\'';
			}
		}

		return $query;
	}
}
