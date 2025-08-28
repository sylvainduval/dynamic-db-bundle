<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\Postgres;

use SylvainDuval\DynamicDbBundle\Domain;
use SylvainDuval\DynamicDbBundle\Domain\Options\Postgres\DatabaseOptions;
use SylvainDuval\DynamicDbBundle\Schema\DatabaseClosableConnectionsInterface;
use SylvainDuval\DynamicDbBundle\Schema\DatabaseQueryGeneratorInterface;

/**
 * @internal
 */
final class DatabaseQueryGenerator implements DatabaseClosableConnectionsInterface, DatabaseQueryGeneratorInterface
{
	public function generateCreateDatabase(Domain\Database $database): string
	{
		$query = 'CREATE DATABASE ' . $database->name;

		if ($database->options instanceof DatabaseOptions) {
			$options = $database->options;

			if ($options->encoding !== null) {
				$query .= ' ENCODING ' . $options->encoding;
			}

			if ($options->owner !== null) {
				$query .= ' OWNER \'' . $options->owner . '\'';
			}

			if ($options->lcCollate !== null) {
				$query .= ' LC_COLLATE \'' . $options->lcCollate . '\'';
			}

			if ($options->lcCtype !== null) {
				$query .= ' LC_CTYPE \'' . $options->lcCtype . '\'';
			}

			if ($options->template !== null) {
				$query .= ' TEMPLATE ' . $options->template;
			}
		}

		return $query;
	}

	public function generateCloseConnections(Domain\Database $database): string
	{
		return 'SELECT pg_terminate_backend(pid) FROM pg_stat_activity WHERE datname = \'' . $database->name . '\' AND pid <> pg_backend_pid();';
	}

	public function generateDeleteDatabase(Domain\Database $database): string
	{
		return 'DROP DATABASE ' . $database->name;
	}
}
