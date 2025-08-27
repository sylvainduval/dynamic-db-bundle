<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Database\Mysqli;

use mysqli_stmt;
use SylvainDuval\DynamicDbBundle\Database\DatabaseStatementAdapterInterface;
use SylvainDuval\DynamicDbBundle\Exception\QueryException;

/**
 * @internal
 */
final class MysqliStatementAdapter implements DatabaseStatementAdapterInterface
{
	public function __construct(private readonly mysqli_stmt $statement) {}

	public function execute(array $params = []): bool
	{
		if (!empty($params)) {
			// Simple typage automatique (peut être amélioré)
			$types = \str_repeat('s', \count($params)); // tous les paramètres en tant que chaînes
			$this->statement->bind_param($types, ...$params);
		}

		return $this->statement->execute();
	}

	public function fetch(): array|false
	{
		$result = $this->statement->get_result();
		if ($result === false) {
			$errorCode = \mysqli_stmt_errno($this->statement);
			if ($errorCode !== 0) {
				throw new QueryException('An error occured while fetching query result (' . $errorCode . ').');
			}

			return false;
		}

		return $result->fetch_assoc() ?? false;
	}

	public function fetchAll(): array
	{
		$result = $this->statement->get_result();
		if ($result === false) {
			$errorCode = \mysqli_stmt_errno($this->statement);
			if ($errorCode !== 0) {
				throw new QueryException('An error occured while fetching all query results (' . $errorCode . ').');
			}

			return [];
		}


		return $result->fetch_all(MYSQLI_ASSOC);
	}
}
