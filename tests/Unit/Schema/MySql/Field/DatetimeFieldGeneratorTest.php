<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Tests\Unit\Schema\MySql\Field;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SylvainDuval\DynamicDbBundle\Domain\Field\Datetime;
use SylvainDuval\DynamicDbBundle\Schema\MySql\Field\DatetimeFieldGenerator;

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
				'foo DATETIME NOT NULL',
			],
			'nullable without default current' => [
				new Datetime('foo', true),
				'foo DATETIME NULL DEFAULT NULL',
			],
			'nullable with default current' => [
				new Datetime('foo', true, true),
				'foo DATETIME NULL DEFAULT NOW()',
			],
			'not nullable with default current' => [
				new Datetime('foo', false, true),
				'foo DATETIME NOT NULL DEFAULT NOW()',
			],
		];
	}

	#[DataProvider('casesProvider')]
	public function testGenerateFieldDefinition(Datetime $field, string $expected): void
	{
		$this->assertEquals($expected, $this->generator->generateFieldDefinition($field));
	}
}
