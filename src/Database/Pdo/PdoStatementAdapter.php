<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Database\Pdo;

use PDO;
use PDOStatement;
use SylvainDuval\DynamicDbBundle\Database\DatabaseStatementAdapterInterface;

/**
 * @internal
 */
final class PdoStatementAdapter implements DatabaseStatementAdapterInterface
{
	public function __construct(private readonly PDOStatement $statement) {}

	public function execute(array $params = []): bool
	{
		return $this->statement->execute($params);
	}

	public function fetch(): array|false
	{
		/** @var array<string, scalar|null>|false $result */
		$result = $this->statement->fetch(PDO::FETCH_ASSOC);

		return $result;
	}

	public function fetchAll(): array
	{
		$result = $this->statement->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}
}
