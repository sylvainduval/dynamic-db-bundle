<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Tests\Unit\Schema\Postgres;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SylvainDuval\DynamicDbBundle\Domain\Database;
use SylvainDuval\DynamicDbBundle\Domain\Options\Postgres\DatabaseOptions;
use SylvainDuval\DynamicDbBundle\Schema\Postgres\DatabaseQueryGenerator;

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
				new Database('foo', new DatabaseOptions('UTF8', 'me', 'fr_FR.UTF-8', 'en_US.UTF-8', 'template0')),
				'CREATE DATABASE foo ENCODING UTF8 OWNER \'me\' LC_COLLATE \'fr_FR.UTF-8\' LC_CTYPE \'en_US.UTF-8\' TEMPLATE template0',
			],
			'database with only charset' => [
				new Database('foo', new DatabaseOptions('UTF8')),
				'CREATE DATABASE foo ENCODING UTF8',
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
