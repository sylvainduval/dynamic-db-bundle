<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema;

use SylvainDuval\DynamicDbBundle\Database\DatabaseAdapterInterface;
use SylvainDuval\DynamicDbBundle\Domain;
use SylvainDuval\DynamicDbBundle\Exception\QueryException;

final class ChangeSet
{
	/** @var string[] $statements */
	private $statements = [];

	public function __construct(
		private readonly Domain\Enum\Driver $driver,
		private readonly DatabaseAdapterInterface $db
	) {}

	public function createTable(Domain\Table $table): self
	{
		$this->statements[] = $this->db->getTableGenerator()->generateCreateTable($table);

		return $this;
	}

	public function deleteTable(Domain\Table $table): self
	{
		$this->statements[] = $this->db->getTableGenerator()->generateDeleteTable($table);

		return $this;
	}

	public function createField(Domain\Table $table, Domain\Field\FieldInterface $field): self
	{
		if ($this->isPostgres() && ($field instanceof Domain\Field\Geometry || $field instanceof Domain\Field\Point)) {
			$this->statements[] = 'CREATE EXTENSION IF NOT EXISTS postgis';
		}
		$this->statements[] = $this->db->getFieldGenerator()->generateCreateField($table, $field);

		return $this;
	}

	public function renameField(Domain\Table $table, string $fromFieldName, string $toFieldName): self
	{
		$this->statements[] = $this->db->getFieldGenerator()->generateRenameField($table, $fromFieldName, $toFieldName);

		return $this;
	}

	public function changeField(Domain\Table $table, Domain\Field\FieldInterface $fromField, Domain\Field\FieldInterface $toField): self
	{
		$this->statements[] = $this->db->getFieldGenerator()->generateChangeField($table, $fromField, $toField);

		return $this;
	}

	public function deleteField(Domain\Table $table, string $fieldName): self
	{
		$this->statements[] = $this->db->getFieldGenerator()->generateDeleteField($table, $fieldName);

		return $this;
	}

	public function createIndex(Domain\Table $table, Domain\Index $index): self
	{
		$this->statements[] = $this->db->getTableGenerator()->generateCreateIndex($table, $index);

		return $this;
	}

	public function deleteIndex(Domain\Table $table, string $indexName): self
	{
		$this->statements[] = $this->db->getTableGenerator()->generateDeleteIndex($table, $indexName);

		return $this;
	}

	public function apply(): void
	{
		$statements = !$this->isPostgres() ? $this->statements : [
			'START TRANSACTION',
			...$this->statements,
			'COMMIT',
		];

		foreach ($statements as $query) {
			try {
				$this->db->query($query);
			} catch (QueryException $exception) {
				if ($this->isPostgres()) {
					$this->db->query('ROLLBACK');
				}
				throw $exception;
			}
		}
	}

	private function isPostgres(): bool
	{
		return $this->driver === Domain\Enum\Driver::PostgreSQL;
	}
}
