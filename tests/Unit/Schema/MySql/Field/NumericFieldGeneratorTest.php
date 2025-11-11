<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Tests\Unit\Schema\MySql\Field;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SylvainDuval\DynamicDbBundle\Domain\Field\Numeric;
use SylvainDuval\DynamicDbBundle\Schema\MySql\Field\NumericFieldGenerator;

final class NumericFieldGeneratorTest extends TestCase
{
	private readonly NumericFieldGenerator $generator;

	protected function setUp(): void
	{
		$this->generator = new NumericFieldGenerator();
	}

	public static function casesProvider(): array
	{
		return [
			'tinyint signed' => [
				new Numeric('foo', -128, 127),
				'foo TINYINT NOT NULL',
			],
			'tinyint unsigned' => [
				new Numeric('foo', 0, 255),
				'foo TINYINT UNSIGNED NOT NULL',
			],
			'smallint signed' => [
				new Numeric('foo', -32768, 32767),
				'foo SMALLINT NOT NULL',
			],
			'smallint unsigned' => [
				new Numeric('foo', 0, 65535),
				'foo SMALLINT UNSIGNED NOT NULL',
			],
			'mediumint signed' => [
				new Numeric('foo', -8388608, 8388607),
				'foo MEDIUMINT NOT NULL',
			],
			'mediumint unsigned' => [
				new Numeric('foo', 0, 16777215),
				'foo MEDIUMINT UNSIGNED NOT NULL',
			],
			'int signed' => [
				new Numeric('foo', -2147483648, 2147483647),
				'foo INT NOT NULL',
			],
			'int unsigned' => [
				new Numeric('foo', 0, 4294967295),
				'foo INT UNSIGNED NOT NULL',
			],
			'bigint signed' => [
				new Numeric('foo', -9999999999, 9999999999),
				'foo BIGINT NOT NULL',
			],
			'bigint unsigned' => [
				new Numeric('foo', 0, 99999999999),
				'foo BIGINT UNSIGNED NOT NULL',
			],
			'decimal with 2 decimals' => [
				new Numeric('foo', 0, 9999, 2),
				'foo DECIMAL(6, 2) NOT NULL',
			],
			'nullable without default' => [
				new Numeric('foo', 0, 100, 0, false, true),
				'foo TINYINT UNSIGNED NULL DEFAULT NULL',
			],
			'nullable with default' => [
				new Numeric('foo', 0, 100, 0, false, true, 5),
				'foo TINYINT UNSIGNED NULL DEFAULT 5',
			],
			'not nullable with default' => [
				new Numeric('foo', 0, 100, 0, false, false, 10),
				'foo TINYINT UNSIGNED NOT NULL DEFAULT 10',
			],
			'auto increment primary key' => [
				new Numeric('foo', 0, 255, 0, true),
				'foo TINYINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
			],
		];
	}

	#[DataProvider('casesProvider')]
	public function testGenerateFieldDefinition(Numeric $field, string $expected): void
	{
		$this->assertSame($expected, $this->generator->generateFieldDefinition($field));
	}

	public function testExceptionThrownForInvalidField(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Expected Numeric');

		$mock = $this->createMock(\SylvainDuval\DynamicDbBundle\Domain\Field\FieldInterface::class);
		$this->generator->generateFieldDefinition($mock);
	}

	public function testExceptionThrownWhenMinGreaterThanMax(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('min value must be less than max');

		$field = new Numeric('bad', 100, 10);
		$this->generator->generateFieldDefinition($field);
	}
}
