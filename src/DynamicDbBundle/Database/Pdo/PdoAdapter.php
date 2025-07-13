<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Database\Pdo;

use PDO;
use PDOException;
use SylvainDuval\DynamicDbBundle\Database\DatabaseAdapterInterface;
use SylvainDuval\DynamicDbBundle\Database\DatabaseStatementAdapterInterface;
use SylvainDuval\DynamicDbBundle\Domain\Enum\Server;
use SylvainDuval\DynamicDbBundle\Exception\QueryException;
use SylvainDuval\DynamicDbBundle\Schema;
use Traversable;

/**
 * @internal
 */
final class PdoAdapter implements DatabaseAdapterInterface
{
	private readonly Schema\DatabaseQueryGeneratorInterface $databaseQueryGenerator;
	private readonly Schema\TableQueryGeneratorInterface $tableQueryGenerator;
	private readonly Schema\FieldQueryGeneratorInterface $fieldQueryGenerator;

	public function __construct(private readonly PDO $pdo, Server $server)
	{
		$queryGeneratorResolver = new Schema\QueryGeneratorResolver($server);
		$this->databaseQueryGenerator = $queryGeneratorResolver->resolveDatabase();
		$this->tableQueryGenerator = $queryGeneratorResolver->resolveTable();
		$this->fieldQueryGenerator = $queryGeneratorResolver->resolveField();
	}

	public function query(string $sql): Traversable
	{
		try {
			$result = $this->pdo->query($sql);
			if ($result === false) {
				$errorCode = $this->pdo->errorCode();
				throw new QueryException('An error occured while querying (' . $errorCode . ').');
			}
		} catch (PDOException $exception) {
			$errorCode = $this->pdo->errorCode();
			throw new QueryException('An error occured while querying (' . $errorCode . '): ' . $exception->getMessage());
		}

		return $result;
	}

	public function prepare(string $sql): DatabaseStatementAdapterInterface
	{
		$stmt = $this->pdo->prepare($sql);

		return new PdoStatementAdapter($stmt);
	}

	public function getDatabaseGenerator(): Schema\DatabaseQueryGeneratorInterface
	{
		return $this->databaseQueryGenerator;
	}

	public function getTableGenerator(): Schema\TableQueryGeneratorInterface
	{
		return $this->tableQueryGenerator;
	}

	public function getFieldGenerator(): Schema\FieldQueryGeneratorInterface
	{
		return $this->fieldQueryGenerator;
	}
}
