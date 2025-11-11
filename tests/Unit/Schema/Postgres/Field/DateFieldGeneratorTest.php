<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Tests\Unit\Schema\Postgres\Field;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SylvainDuval\DynamicDbBundle\Domain\Field\Date;
use SylvainDuval\DynamicDbBundle\Schema\Postgres\Field\DateFieldGenerator;

final class DateFieldGeneratorTest extends TestCase
{
	private readonly DateFieldGenerator $generator;

	public function setUp(): void
	{
		$this->generator = new DateFieldGenerator();
	}

	public static function casesProvider(): array
	{
		return [
			'default' => [
				new Date('foo'),
				'foo DATE NOT NULL',
			],
			'nullable without default current' => [
				new Date('foo', true),
				'foo DATE NULL DEFAULT NULL',
			],
			'nullable with default current' => [
				new Date('foo', true, true),
				'foo DATE NULL DEFAULT CURRENT_DATE',
			],
			'not nullable with default current' => [
				new Date('foo', false, true),
				'foo DATE NOT NULL DEFAULT CURRENT_DATE',
			],
		];
	}

	#[DataProvider('casesProvider')]
	public function testGenerateFieldDefinition(Date $field, string $expected): void
	{
		$this->assertEquals($expected, $this->generator->generateFieldDefinition($field));
	}
}
