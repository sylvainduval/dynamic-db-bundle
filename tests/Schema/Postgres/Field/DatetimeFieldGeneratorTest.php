<?php

declare(strict_types=1);

namespace Tests\SylvainDuval\DynamicDbBundle\Schema\Postgres\Field;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SylvainDuval\DynamicDbBundle\Domain\Field\Datetime;
use SylvainDuval\DynamicDbBundle\Schema\Postgres\Field\DatetimeFieldGenerator;

final class DatetimeFieldGeneratorTest extends TestCase
{
	private readonly DatetimeFieldGenerator $generator;

	public function setUp(): void
	{
		$this->generator = new DatetimeFieldGenerator();
	}

	public static function casesProvider(): array
	{
		return [
			'default' => [
				new Datetime('foo'),
				'foo TIMESTAMP NOT NULL',
			],
			'nullable without default current' => [
				new Datetime('foo', true),
				'foo TIMESTAMP NULL DEFAULT NULL',
			],
			'nullable with default current' => [
				new Datetime('foo', true, true),
				'foo TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP',
			],
			'not nullable with default current' => [
				new Datetime('foo', false, true),
				'foo TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
			],
		];
	}

	#[DataProvider('casesProvider')]
	public function testGenerateFieldDefinition(Datetime $field, string $expected): void
	{
		$this->assertEquals($expected, $this->generator->generateFieldDefinition($field));
	}
}
