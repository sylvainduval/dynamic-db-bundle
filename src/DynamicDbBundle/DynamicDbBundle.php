<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle;

use SylvainDuval\DynamicDbBundle\Exception\QueryException;
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

	public function helloMariaDb(): void
	{
		$database = new Domain\Database(
			'toto',
			new Domain\Options\MariaDb\DatabaseOptions(
				$this->config->charset,
				'utf8mb4_general_ci',
				'test mariadb'
			),
		);

		$table = new Domain\Table(
			$database,
			'ma_table',
			new Domain\Options\MariaDb\TableOptions(false, 'InnoDB'),
			[
				new Domain\Field\TextField('first_field', 5, true, true, 'abcde'),
			]
		);

		$this
			->createDatabase($database)
			->createTable($table)
			->createField($table, new Domain\Field\TextField('second_field'))
			->renameField($table, 'second_field', 'third_field')
			->changeField(
				$table,
				new Domain\Field\TextField('third_field'),
				new Domain\Field\TextField('third_field', 100, false, true, 'ici')
			)
			->deleteField($table, 'third_field')
			->createField($table, new Domain\Field\NumericField('fourth_field'))
			->createField($table, new Domain\Field\NumericField('fifth_field', 10, 800000, 2, false, true))
			->createField($table, new Domain\Field\NumericField('id', 0, 10000000, 0, true))
			->createField($table, new Domain\Field\UuidField('uuid', true, '877fd663-5e95-495b-80a1-000c2d38122d'))
			->createField($table, new Domain\Field\Boolean('oui_non', true, true))
			->deleteTable($table)
			->deleteDatabase($database)
		;
		//$result = $this->getDb()->query('UPDATE sample.test SET col_test=1');
		//var_dump($result);
		//
		//$statement = $this->getDb()->prepare('SHOW databases');
		//$statement->execute([]);
		//$rows = $statement->fetchAll();
		//
		//print_r($rows);
	}

	public function helloPostgres(): void
	{
		$database = new Domain\Database(
			'toto',
			new Domain\Options\Postgres\DatabaseOptions(
				$this->config->charset,
				'user',
				'en_US.UTF-8',
				'en_US.UTF-8',
				'template0'
			),
		);

		$table = new Domain\Table(
			$database,
			'ma_table',
			new Domain\Options\Postgres\TableOptions(false),
			[
				new Domain\Field\TextField('first_field', 5, true, true, 'abcde'),
			]
		);

		$this
			->createDatabase($database)
			->createTable($table)
			->createField($table, new Domain\Field\TextField('second_field'))
			->renameField($table, 'second_field', 'third_field')
			->changeField(
				$table,
				new Domain\Field\TextField('third_field'),
				new Domain\Field\TextField('third_field', 100, default: 'ici')
			)
			->deleteField($table, 'third_field')
			->createField($table, new Domain\Field\NumericField('fourth_field'))
			->createField($table, new Domain\Field\NumericField('fifth_field', 10, 800000, 2, false, true))
			->createField($table, new Domain\Field\NumericField('id', 0, 10000000, 0, true))
			->createField($table, new Domain\Field\UuidField('uuid', true, '877fd663-5e95-495b-80a1-000c2d38122d'))
			->createField($table, new Domain\Field\Boolean('oui_non', true, true))
			->deleteTable($table)
			->deleteDatabase($database)
		;
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

	public function createTable(Domain\Table $table): self
	{
		$db = $this->getDb($table->database->name);
		$sql = $db->getTableGenerator()->generateCreateTable($table);
		$db->query($sql);

		return $this;
	}

	public function deleteTable(Domain\Table $table): self
	{
		$db = $this->getDb($table->database->name);
		$sql = $db->getTableGenerator()->generateDeleteTable($table);
		$db->query($sql);

		return $this;
	}

	public function createField(Domain\Table $table, Domain\Field\FieldInterface $field): self
	{
		$db = $this->getDb($table->database->name);
		$sql = $db->getFieldGenerator()->generateCreateField($table, $field);
		$db->query($sql);

		return $this;
	}

	public function renameField(Domain\Table $table, string $fromFieldName, string $toFieldName): self
	{
		$db = $this->getDb($table->database->name);
		$sql = $db->getFieldGenerator()->generateRenameField($table, $fromFieldName, $toFieldName);
		$db->query($sql);

		return $this;
	}

	public function changeField(Domain\Table $table, Domain\Field\FieldInterface $fromField, Domain\Field\FieldInterface $toField): self
	{
		$db = $this->getDb($table->database->name);
		$sql = $db->getFieldGenerator()->generateChangeField($table, $fromField, $toField);
		$db->query($sql);

		return $this;
	}

	public function deleteField(Domain\Table $table, string $fieldName): self
	{
		$db = $this->getDb($table->database->name);
		$sql = $db->getFieldGenerator()->generateDeleteField($table, $fieldName);
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
