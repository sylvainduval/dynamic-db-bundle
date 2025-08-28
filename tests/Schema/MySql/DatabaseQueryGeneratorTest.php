<?php

declare(strict_types=1);

namespace Tests\SylvainDuval\DynamicDbBundle\Schema\MySql;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SylvainDuval\DynamicDbBundle\Domain\Database;
use SylvainDuval\DynamicDbBundle\Domain\Options\MySql\DatabaseOptions;
use SylvainDuval\DynamicDbBundle\Schema\MySql\DatabaseQueryGenerator;

final class DatabaseQueryGeneratorTest extends TestCase
{
	private readonly DatabaseQueryGenerator $generator;

	public function setUp(): void
	{
		$this->generator = new DatabaseQueryGenerator();
	}

	public static function createDatabaseProvider(): array
	{
		return [
			'database with all attributes' => [
				new Database('foo', new DatabaseOptions('utf8mb4', 'utf8mb4_general_ci', 'bar')),
				'CREATE DATABASE foo CHARACTER SET = \'utf8mb4\' COLLATE = \'utf8mb4_general_ci\' COMMENT = \'bar\'',
			],
			'database with only charset' => [
				new Database('foo', new DatabaseOptions('utf8mb4', null, null)),
				'CREATE DATABASE foo CHARACTER SET = \'utf8mb4\'',
			],
		];
	}

	public static function deleteDatabaseProvider(): array
	{
		return [
			'database with name' => [
				new Database('foo'),
				'DROP DATABASE foo',
			],

		];
	}

	#[DataProvider('createDatabaseProvider')]
	public function testGenerateCreateDatabase(Database $database, string $expected): void
	{
		$this->assertEquals($expected, $this->generator->generateCreateDatabase($database));
	}

	#[DataProvider('deleteDatabaseProvider')]
	public function testGenerateDeleteDatabase(Database $database, string $expected): void
	{
		$this->assertEquals($expected, $this->generator->generateDeleteDatabase($database));
	}
}
