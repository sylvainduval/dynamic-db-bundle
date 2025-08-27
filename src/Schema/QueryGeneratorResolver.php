<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema;

use SylvainDuval\DynamicDbBundle\Domain\Enum\Server;
use SylvainDuval\DynamicDbBundle\Schema;

/**
 * @internal
 */
final class QueryGeneratorResolver
{
	public function __construct(private readonly Server $server) {}

	public function resolveDatabase(): DatabaseQueryGeneratorInterface
	{
		return match ($this->server) {
			Server::MySQL => new Schema\MySql\DatabaseQueryGenerator(),
			Server::MariaDB => new Schema\MariaDb\DatabaseQueryGenerator(),
			Server::PostgreSQL => new Schema\Postgres\DatabaseQueryGenerator(),
		};
	}

	public function resolveTable(): TableQueryGeneratorInterface
	{
		return match ($this->server) {
			Server::MySQL => new Schema\MySql\TableQueryGenerator(new Schema\MySql\FieldQueryGenerator()),
			Server::MariaDB => new Schema\MariaDb\TableQueryGenerator(new Schema\MariaDb\FieldQueryGenerator()),
			Server::PostgreSQL => new Schema\Postgres\TableQueryGenerator(new Schema\Postgres\FieldQueryGenerator()),
		};
	}

	public function resolveField(): FieldQueryGeneratorInterface
	{
		return match ($this->server) {
			Server::MySQL => new Schema\MySql\FieldQueryGenerator(),
			Server::MariaDB => new Schema\MariaDb\FieldQueryGenerator(),
			Server::PostgreSQL => new Schema\Postgres\FieldQueryGenerator(),
		};
	}
}
