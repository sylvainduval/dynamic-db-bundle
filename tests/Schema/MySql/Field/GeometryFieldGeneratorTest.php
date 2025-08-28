<?php

declare(strict_types=1);

namespace Tests\SylvainDuval\DynamicDbBundle\Schema\MySql\Field;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SylvainDuval\DynamicDbBundle\Domain\Field\Geometry;
use SylvainDuval\DynamicDbBundle\Schema\MySql\Field\GeometryFieldGenerator;

final class GeometryFieldGeneratorTest extends TestCase
{
	private readonly GeometryFieldGenerator $generator;

	public function setUp(): void
	{
		$this->generator = new GeometryFieldGenerator();
	}

	public static function casesProvider(): array
	{
		return [
			'default' => [
				new Geometry('foo'),
				'foo GEOMETRY NOT NULL',
			],
			'nullable' => [
				new Geometry('foo', true),
				'foo GEOMETRY NULL',
			],
		];
	}

	#[DataProvider('casesProvider')]
	public function testGenerateFieldDefinition(Geometry $field, string $expected): void
	{
		$this->assertEquals($expected, $this->generator->generateFieldDefinition($field));
	}
}
