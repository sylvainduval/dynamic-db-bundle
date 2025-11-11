<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle;

use SylvainDuval\DynamicDbBundle\Exception\QueryException;
use SylvainDuval\DynamicDbBundle\Schema\ChangeSet;
use SylvainDuval\DynamicDbBundle\Schema\DatabaseClosableConnectionsInterface;

/**
 * @phpstan-import-type ConfigurationArray from Domain\Configuration
 */
class DynamicDbBundle
{
	private Domain\Configuration $config;

	/** @var array<string, Database\DatabaseAdapterInterface> */
	private array $db = [];

	/**
	 * @param ConfigurationArray $config
	 */
	public function __construct(array $config)
	{
		$this->config = Domain\Configuration::fromArray($config);
	}

	public function startSchemaChangeSet(Domain\Database $database): ChangeSet
	{
		return new ChangeSet(
			$this->config->driver,
			$this->getDb($database->name)
		);
	}

	/**
	 * @throws QueryException
	 */
	public function createDatabase(Domain\Database $database): self
	{
		$db = $this->getDb($this->config->database);
		$sql = $db->getDatabaseGenerator()->generateCreateDatabase($database);
		$db->query($sql);

		return $this;
	}

	/**
	 * @throws QueryException
	 */
	public function deleteDatabase(Domain\Database $database): self
	{
		$db = $this->getDb($this->config->database);
		if ($db->getDatabaseGenerator() instanceof DatabaseClosableConnectionsInterface) {
			$sql = $db->getDatabaseGenerator()->generateCloseConnections($database);
			$db->query($sql);
		}
		$sql = $db->getDatabaseGenerator()->generateDeleteDatabase($database);
		$db->query($sql);

		return $this;
	}

	private function getDb(?string $database = null): Database\DatabaseAdapterInterface
	{
		if ($database === null) {
			$database = $this->config->database;
		}
		if (!isset($this->db[$database])) {
			$this->db[$database] = Database\DatabaseAdapterFactory::create($this->config->withDatabase($database));
		}

		return $this->db[$database];
	}
}
