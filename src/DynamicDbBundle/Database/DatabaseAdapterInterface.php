<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Database;

use SylvainDuval\DynamicDbBundle\Schema;
use Traversable;

/**
 * @internal
 */
interface DatabaseAdapterInterface
{
	/**
	 * @return Traversable<mixed>
	 */
	public function query(string $sql): Traversable;

	public function prepare(string $sql): DatabaseStatementAdapterInterface;

	public function getDatabaseGenerator(): Schema\DatabaseQueryGeneratorInterface;

	public function getTableGenerator(): Schema\TableQueryGeneratorInterface;

	public function getFieldGenerator(): Schema\FieldQueryGeneratorInterface;
}
