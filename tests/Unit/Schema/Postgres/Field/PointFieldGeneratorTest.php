<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Tests\Unit\Schema\Postgres\Field;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SylvainDuval\DynamicDbBundle\Domain\Field\Point;
use SylvainDuval\DynamicDbBundle\Schema\Postgres\Field\PointFieldGenerator;

final class PointFieldGeneratorTest extends TestCase
{
	private readonly PointFieldGenerator $generator;

	public function setUp(): void
	{
		$this->generator = new PointFieldGenerator();
	}

	public static function casesProvider(): array
	{
		return [
			'default' => [
				new Point('foo'),
				'foo GEOGRAPHY(POINT) NOT NULL',
			],
			'nullable' => [
				new Point('foo', true),
				'foo GEOGRAPHY(POINT) NULL',
			],
		];
	}

	#[DataProvider('casesProvider')]
	public function testGenerateFieldDefinition(Point $field, string $expected): void
	{
		$this->assertEquals($expected, $this->generator->generateFieldDefinition($field));
	}
}
