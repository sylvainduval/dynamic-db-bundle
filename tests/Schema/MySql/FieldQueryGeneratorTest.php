<?php

declare(strict_types=1);

namespace Tests\SylvainDuval\DynamicDbBundle\Schema\MySql;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SylvainDuval\DynamicDbBundle\Domain\Field;
use SylvainDuval\DynamicDbBundle\Domain\Table;
use SylvainDuval\DynamicDbBundle\Schema\MySql\FieldQueryGenerator;

final class FieldQueryGeneratorTest extends TestCase
{
	private readonly FieldQueryGenerator $generator;

	public function setUp(): void
	{
		$this->generator = new FieldQueryGenerator();
	}

	public static function createFieldProvider(): array
	{
		return [
			'simple text field' => [
				new Table('bar'),
				new Field\Text('foo'),
				'ALTER TABLE bar ADD foo VARCHAR(254) NOT NULL',
			],
		];
	}

	public static function renameFieldProvider(): array
	{
		return [
			'simple text field' => [
				new Table('bar'),
				'foo',
				'doe',
				'ALTER TABLE bar RENAME COLUMN foo TO doe',
			],
		];
	}

	public static function changeFieldProvider(): array
	{
		return [
			'simple numeric field to simple text field' => [
				new Table('bar'),
				new Field\Numeric('foo'),
				new Field\Text('doe'),
				'ALTER TABLE bar MODIFY doe VARCHAR(254) NOT NULL',
			],
		];
	}

	public static function deleteFieldProvider(): array
	{
		return [
			'simple text field' => [
				new Table('bar'),
				'foo',
				'ALTER TABLE bar DROP COLUMN foo',
			],
		];
	}

	#[DataProvider('createFieldProvider')]
	public function testGenerateCreateField(Table $table, Field\FieldInterface $field, string $expected): void
	{
		$this->assertEquals($expected, $this->generator->generateCreateField($table, $field));
	}

	#[DataProvider('renameFieldProvider')]
	public function testGenerateRenameField(Table $table, string $fromFieldName, string $toFieldName, string $expected): void
	{
		$this->assertEquals($expected, $this->generator->generateRenameField($table, $fromFieldName, $toFieldName));
	}

	#[DataProvider('changeFieldProvider')]
	public function testGenerateChangeField(Table $table, Field\FieldInterface $fromField, Field\FieldInterface $toField, string $expected): void
	{
		$this->assertEquals($expected, $this->generator->generateChangeField($table, $fromField, $toField));
	}

	#[DataProvider('deleteFieldProvider')]
	public function testGenerateDeleteField(Table $table, string $fieldName, string $expected): void
	{
		$this->assertEquals($expected, $this->generator->generateDeleteField($table, $fieldName));
	}
}
