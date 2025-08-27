<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Database\Mysqli;

use mysqli;
use mysqli_sql_exception;
use SylvainDuval\DynamicDbBundle\Database\DatabaseAdapterInterface;
use SylvainDuval\DynamicDbBundle\Database\DatabaseStatementAdapterInterface;
use SylvainDuval\DynamicDbBundle\Domain\Enum\Server;
use SylvainDuval\DynamicDbBundle\Exception\QueryException;
use SylvainDuval\DynamicDbBundle\Schema;
use Traversable;

/**
 * @internal
 */
final class MysqliAdapter implements DatabaseAdapterInterface
{
	private readonly Schema\DatabaseQueryGeneratorInterface $databaseQueryGenerator;
	private readonly Schema\TableQueryGeneratorInterface $tableQueryGenerator;
	private readonly Schema\FieldQueryGeneratorInterface $fieldQueryGenerator;

	public function __construct(private readonly mysqli $mysqli, Server $server)
	{
		$queryGeneratorResolver = new Schema\QueryGeneratorResolver($server);
		$this->databaseQueryGenerator = $queryGeneratorResolver->resolveDatabase();
		$this->tableQueryGenerator = $queryGeneratorResolver->resolveTable();
		$this->fieldQueryGenerator = $queryGeneratorResolver->resolveField();
	}

	public function query(string $sql): Traversable
	{
		try {
			$result = $this->mysqli->query($sql);
			if ($result === false) {
				$errorCode = $this->mysqli->errno;
				throw new QueryException('An error occured while querying (' . $errorCode . '): ' . $this->mysqli->error);
			}
		} catch (mysqli_sql_exception $exception) {
			$errorCode = $this->mysqli->errno;
			throw new QueryException('An error occured while querying (' . $errorCode . '): ' . $exception->getMessage());
		}

		if ($result === true) {
			return (function () {
				yield from [];
			})();
		}

		return (function () use ($result) {
			while ($row = $result->fetch_assoc()) {
				yield $row;
			}
		})();
	}

	public function prepare(string $sql): DatabaseStatementAdapterInterface
	{
		$stmt = $this->mysqli->prepare($sql);

		if ($stmt === false) {
			throw new QueryException('Erreur de préparation : ' . $this->mysqli->error);
		}

		return new MysqliStatementAdapter($stmt);
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
