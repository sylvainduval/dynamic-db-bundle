<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Tests\Integration;

use PHPUnit\Framework\TestCase;
use SylvainDuval\DynamicDbBundle\Domain;
use SylvainDuval\DynamicDbBundle\DynamicDbBundle;
use SylvainDuval\DynamicDbBundle\Exception\QueryException;

final class DynamicDbBundleMySqlTest extends TestCase
{
	private readonly DynamicDbBundle $dynamicDb;

	public function setUp(): void
	{
		$this->dynamicDb = new DynamicDbBundle([
			'driver' => 'mysql',
			'host' => \getenv('MYSQL_DB_HOST'),
			'port' => (int) \getenv('MYSQL_DB_PORT'),
			'user' => \getenv('MYSQL_DB_USER'),
			'password' => \getenv('MYSQL_DB_PASSWORD'),
			'charset' => \getenv('MYSQL_DB_CHARSET'),
		]);
	}

	public function testMySqlDatabase()
	{
		$database = new Domain\Database(
			'toto',
			new Domain\Options\MariaDb\DatabaseOptions(
				\getenv('MYSQL_DB_CHARSET'),
				'utf8mb4_general_ci',
				'test mariadb'
			),
		);

		$table = new Domain\Table(
			'ma_table',
			new Domain\Options\MariaDb\TableOptions(false, 'InnoDB'),
			[
				new Domain\Field\Text('first_field', 5, true, true, 'abcde'),
			]
		);

		$this->dynamicDb->createDatabase($database);

		$this->dynamicDb
			->startSchemaChangeSet($database)
			->createTable($table)
			->createField($table, new Domain\Field\Text('second_field'))
			->createIndex($table, new Domain\Index('index1', ['second_field']))
			->renameField($table, 'second_field', 'third_field')
			->changeField(
				$table,
				new Domain\Field\Text('third_field'),
				new Domain\Field\Text('third_field', 100, false, true, 'ici')
			)
			->deleteIndex($table, 'index1')
			->deleteField($table, 'third_field')
			->createField($table, new Domain\Field\Numeric('fourth_field'))
			->createIndex($table, new Domain\Index('index3', ['fourth_field'], new Domain\Options\MariaDb\IndexOptions(Domain\Enum\MySql\IndexType::Unique)))
			->createField($table, new Domain\Field\Numeric('fifth_field', 10, 800000, 2, false, true))
			->createField($table, new Domain\Field\Numeric('id', 0, 10000000, 0, true))
			->createField($table, new Domain\Field\Uuid('uuid', true, '877fd663-5e95-495b-80a1-000c2d38122d'))
			->createField($table, new Domain\Field\Boolean('oui_non', true, true))
			->createField($table, new Domain\Field\Json('tableau', true, ['a' => 'b\'c']))
			->createField($table, new Domain\Field\Point('geopoint', true))
			->createField($table, new Domain\Field\Geometry('geo', false))
			->createIndex(
				$table,
				new Domain\Index('index2', ['geo'], new Domain\Options\MariaDb\IndexOptions(Domain\Enum\MySql\IndexType::Spacial))
			)
			->createField($table, new Domain\Field\Date('ddate', false, true))
			->createField($table, new Domain\Field\Datetime('ddatetime', false, true))
			->deleteTable($table)
			->apply()
		;

		$this->dynamicDb->deleteDatabase($database);

		$this->expectException(QueryException::class);
		$this->expectExceptionMessage("An error occured while querying (HY000): SQLSTATE[HY000]: General error: 1008 Can't drop database 'toto'; database doesn't exist");
		$this->dynamicDb->deleteDatabase($database);
	}

}
