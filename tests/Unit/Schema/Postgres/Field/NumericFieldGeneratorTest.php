<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Tests\Unit\Schema\Postgres\Field;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SylvainDuval\DynamicDbBundle\Domain\Field\Numeric;
use SylvainDuval\DynamicDbBundle\Schema\Postgres\Field\NumericFieldGenerator;

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
			'smallint no default' => [
				new Numeric('foo', -10, 100),
				'foo SMALLINT NOT NULL',
			],
			'integer nullable with default' => [
				new Numeric('foo', -10000, 100000, 0, false, true, 42),
				'foo INTEGER DEFAULT 42',
			],
			'bigint not null no default' => [
				new Numeric('foo', -999999999999, 999999999999, 0, false, false, null),
				'foo BIGINT NOT NULL',
			],
			'decimal with 2 decimals' => [
				new Numeric('foo', 0, 9999, 2),
				'foo NUMERIC(6, 2) NOT NULL',
			],
			'nullable decimal with default' => [
				new Numeric('foo', 0, 9999, 2, false, true, 3.14),
				'foo NUMERIC(6, 2) DEFAULT 3.14',
			],
			'smallserial' => [
				new Numeric('foo', 0, 255, 0, true),
				'foo SMALLSERIAL NOT NULL',
			],
			'serial' => [
				new Numeric('foo', 0, 1_000_000, 0, true),
				'foo SERIAL NOT NULL',
			],
			'bigserial' => [
				new Numeric('foo', 0, 9_000_000_000, 0, true),
				'foo BIGSERIAL NOT NULL',
			],
			'nullable with null default' => [
				new Numeric('foo', 0, 10, 0, false, true, null),
				'foo SMALLINT DEFAULT NULL',
			],
		];
	}

	#[DataProvider('casesProvider')]
	public function testGenerateFieldDefinition(Numeric $field, string $expected): void
	{
		$this->assertSame($expected, $this->generator->generateFieldDefinition($field));
	}
}
