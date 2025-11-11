<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Tests\Unit\Schema\MySql\Field;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SylvainDuval\DynamicDbBundle\Domain\Field\Point;
use SylvainDuval\DynamicDbBundle\Schema\MySql\Field\PointFieldGenerator;

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
				'foo POINT NOT NULL',
			],
			'nullable' => [
				new Point('foo', true),
				'foo POINT NULL',
			],
		];
	}

	#[DataProvider('casesProvider')]
	public function testGenerateFieldDefinition(Point $field, string $expected): void
	{
		$this->assertEquals($expected, $this->generator->generateFieldDefinition($field));
	}
}
