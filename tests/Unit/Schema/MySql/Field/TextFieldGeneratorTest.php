<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Tests\Unit\Schema\MySql\Field;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SylvainDuval\DynamicDbBundle\Domain\Field\Text;
use SylvainDuval\DynamicDbBundle\Schema\MySql\Field\TextFieldGenerator;

final class TextFieldGeneratorTest extends TestCase
{
	private readonly TextFieldGenerator $generator;

	protected function setUp(): void
	{
		$this->generator = new TextFieldGenerator();
	}

	public static function casesProvider(): array
	{
		return [
			// CHAR
			'char fixed length' => [
				new Text('foo', 50, true),
				'foo CHAR(50) NOT NULL',
			],

			// VARCHAR
			'varchar default length' => [
				new Text('foo'),
				'foo VARCHAR(254) NOT NULL',
			],
			'varchar custom length' => [
				new Text('foo', 500),
				'foo VARCHAR(500) NOT NULL',
			],

			// TEXT types
			'tinytext' => [
				new Text('foo', 255),
				'foo VARCHAR(255) NOT NULL',
			],
			'text' => [
				new Text('foo', 60000),
				'foo TEXT NOT NULL',
			],
			'mediumtext' => [
				new Text('foo', 1000000),
				'foo MEDIUMTEXT NOT NULL',
			],
			'longtext' => [
				new Text('foo', 4000000000),
				'foo LONGTEXT NOT NULL',
			],

			// NULL and DEFAULT
			'nullable without default' => [
				new Text('foo', 1000, false, true),
				'foo VARCHAR(1000) NULL DEFAULT NULL',
			],
			'nullable with default' => [
				new Text('foo', 100, false, true, 'bar'),
				'foo VARCHAR(100) NULL DEFAULT \'bar\'',
			],
			'not nullable with default' => [
				new Text('status', 20, false, false, 'bar'),
				'status VARCHAR(20) NOT NULL DEFAULT \'bar\'',
			],
			'default with quote' => [
				new Text('foo', 100, false, false, 'bar'),
				'foo VARCHAR(100) NOT NULL DEFAULT \'bar\'',
			],
		];
	}

	#[DataProvider('casesProvider')]
	public function testGenerateFieldDefinition(Text $field, string $expected): void
	{
		$this->assertSame($expected, $this->generator->generateFieldDefinition($field));
	}

	public function testInvalidFieldThrowsException(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Expected Text');

		$mock = $this->createMock(\SylvainDuval\DynamicDbBundle\Domain\Field\FieldInterface::class);
		$this->generator->generateFieldDefinition($mock);
	}

	public function testLengthZeroThrowsException(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Length must be a positive integer.');

		$field = new Text('invalid', 0);
		$this->generator->generateFieldDefinition($field);
	}

	public function testTooLargeLengthThrowsException(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Length too large for supported TEXT types.');

		$field = new Text('huge', 5000000000);
		$this->generator->generateFieldDefinition($field);
	}
}
